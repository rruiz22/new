<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="list" class="icon-sm me-1"></i>
                    All CarWash Orders <span id="allOrdersCount"></span>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshAllOrdersTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="allOrdersTable" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Order #</th>
                                <th scope="col">Tag/Stock</th>
                                <th scope="col">Vehicle</th>
                                <th scope="col">Service</th>
                                <th scope="col">Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Helper function to convert hex color to rgba with opacity
function hexToRgba(hex, opacity) {
    // Remove the hash if present
    hex = hex.replace('#', '');
    
    // Parse the hex values
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
}

$(document).ready(function() {
    // Initialize DataTable
    var allOrdersTable = $('#allOrdersTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('car_wash/getAllActiveOrders') ?>",
            "type": "GET",
            "data": function(d) {
                // Add global filters if available
                if (typeof window.getCurrentFilters === 'function') {
                    const filters = window.getCurrentFilters();
                    d.client_filter = filters.client_filter || '';
                    d.status_filter = filters.status_filter || '';
                    d.service_filter = filters.service_filter || '';
                    d.date_from_filter = filters.date_from_filter || '';
                    d.date_to_filter = filters.date_to_filter || '';
                }
                return d;
            },
            "dataSrc": function(json) {
                // Update order count
                $('#allOrdersCount').text('(' + json.data.length + ')');
                return json.data;
            }
        },
        "columns": [
            {
                "data": "order_number",
                "render": function(data, type, row) {
                    let html = `<div><a href="<?= base_url('car_wash/view/') ?>${row.id}" class="link-primary fw-bold fs-15">${data}</a>`;
                    
                    // Add comments icon right after order ID
                    if (row.comments_count && row.comments_count > 0) {
                        html += `<a href="#" class="comments-icon ms-2" data-order-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Loading comments..." onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('car_wash/view/') ?>${row.id}#comments-section';">`;
                        html += '<i class="ri-chat-3-line"></i>';
                        html += '<span class="comment-count">' + row.comments_count + '</span>';
                        html += '</a>';
                    }
                    
                    // Add client name below order ID with business icon
                    if (row.client_name && row.client_name !== 'N/A') {
                        html += `<div class="text-muted small mt-1">
                            <i class="ri-building-line me-1"></i>${row.client_name}`;
                        
                        // Add notes icon right after client name
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && row.internal_notes_count > 0) {
                            html += `<a href="#" class="notes-icon ms-2" data-order-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('car_wash/view/') ?>${row.id}#internal-notes-card';">`;
                            html += '<i class="ri-file-lock-line"></i>';
                            html += '<span class="note-count">' + row.internal_notes_count + '</span>';
                            html += '</a>';
                        }
                        <?php endif; ?>
                        
                        html += '</div>';
                    }
                    
                    html += `</div>`;
                    return html;
                }
            },
            {
                "data": "tag_stock",
                "render": function(data, type, row) {
                    let html = data || '<span class="text-muted">-</span>';
                    
                    // Add duplicate icon if there are tag/stock duplicates
                    if (row.duplicates && row.duplicates.has_duplicates && row.duplicates.tag_stock_duplicates.length > 0) {
                        let tooltipContent = '<strong>Tag/Stock Duplicates:</strong><br>';
                        row.duplicates.tag_stock_duplicates.forEach(duplicate => {
                            let duplicateNumber = duplicate.order_number || `CW-${String(duplicate.id).padStart(5, '0')}`;
                            let duplicateDate = new Date(duplicate.created_at).toLocaleDateString();
                            tooltipContent += `• ${duplicateNumber} - ${duplicate.vehicle || 'N/A'} (${duplicateDate})<br>`;
                        });
                        
                        html += ` <i class="ri-file-copy-2-line text-warning ms-1" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-html="true"
                                    title="${tooltipContent}"
                                    style="cursor: help;"></i>`;
                    }
                    
                    // Add priority information below tag/stock if priority is waiter
                    if (row.priority === 'waiter') {
                        html += `<div class="mt-1">
                            <span class="badge bg-danger-subtle text-danger-emphasis px-2 py-1 rounded-pill small">
                                <i class="ri-user-fill me-1"></i>Waiter
                            </span>
                        </div>`;
                    }
                    
                    return html;
                }
            },
            {
                "data": "vehicle",
                "render": function(data, type, row) {
                    let html = `<div><span class="fw-medium">${data || '<span class="text-muted">-</span>'}</span>`;
                    
                    // Add duplicate icon if there are VIN duplicates
                    if (row.duplicates && row.duplicates.has_duplicates && row.duplicates.vin_duplicates.length > 0) {
                        let tooltipContent = '<strong>VIN Duplicates:</strong><br>';
                        row.duplicates.vin_duplicates.forEach(duplicate => {
                            let duplicateNumber = duplicate.order_number || `CW-${String(duplicate.id).padStart(5, '0')}`;
                            let duplicateDate = new Date(duplicate.created_at).toLocaleDateString();
                            tooltipContent += `• ${duplicateNumber} - ${duplicate.vehicle || 'N/A'} (${duplicateDate})<br>`;
                        });
                        
                        html += ` <i class="ri-file-copy-2-line text-warning ms-1" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-html="true"
                                    title="${tooltipContent}"
                                    style="cursor: help;"></i>`;
                    }
                    
                    // Add VIN below vehicle information
                    if (row.vin_number && row.vin_number.trim() !== '') {
                        html += `<div class="text-muted small mt-1">VIN: ${row.vin_number}</div>`;
                    }
                    
                    html += `</div>`;
                    return html;
                }
            },
            {
                "data": "service_name",
                "render": function(data, type, row) {
                    let html = `<div><span class="fw-medium">${data || '<span class="text-muted">No service</span>'}</span>`;
                    
                    // Add status below service name
                    if (row.status) {
                        var badgeClass = '';
                        switch(row.status) {
                            case 'pending': badgeClass = 'bg-warning text-dark'; break;
                            case 'confirmed': badgeClass = 'bg-info text-white'; break;
                            case 'in_progress': badgeClass = 'bg-primary text-white'; break;
                            case 'completed': badgeClass = 'bg-success text-white'; break;
                            case 'cancelled': badgeClass = 'bg-danger text-white'; break;
                            default: badgeClass = 'bg-secondary text-white';
                        }
                        const statusText = row.status.charAt(0).toUpperCase() + row.status.slice(1).replace('_', ' ');
                        html += `<div class="mt-1"><span class="badge ${badgeClass} badge-sm">${statusText}</span></div>`;
                    }
                    
                    html += `</div>`;
                    return html;
                }
            },
            {
                "data": "created_at",
                "render": function(data, type, row) {
                    if (data) {
                        var date = new Date(data);
                        var dateStr = date.toLocaleDateString();
                        var timeStr = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        let html = `<div><span class="fw-medium">${timeStr}</span>`;
                        html += `<div class="text-muted small mt-1">${dateStr}</div>`;
                        html += `</div>`;
                        return html;
                    }
                    return '<span class="text-muted">-</span>';
                }
            },
            {
                "data": "id",
                "orderable": false,
                "searchable": false,
                "responsivePriority": 1,
                "className": "actions-column",
                "render": function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-1 action-buttons">
                            <a href="<?= base_url('car_wash/view/') ?>${data}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                <i class="ri-eye-fill"></i>
                            </a>
                            <a href="#" class="link-success fs-15 edit-carwash-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="ri-edit-fill"></i>
                            </a>
                            <a href="#" class="link-danger fs-15 delete-carwash-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        "order": [[0, "desc"]], // Order by order number descending
        "pageLength": 25,
        "responsive": {
            "details": {
                "type": 'column',
                "target": 'tr'
            }
        },
        "rowCallback": function(row, data) {
            // Apply service color to row if available
            if (data.service_color && data.service_color !== '#007bff') {
                $(row).css('border-left', '4px solid ' + data.service_color);
                $(row).css('background-color', hexToRgba(data.service_color, 0.1));
            }
        },
        "columnDefs": [
            { "responsivePriority": 1, "targets": 0 }, // Order # - always visible
            { "responsivePriority": 1, "targets": 1 }, // Tag/Stock - always visible
            { "responsivePriority": 1, "targets": 2 }, // Vehicle - always visible
            { "responsivePriority": 1, "targets": -1 }, // Actions - always visible
            { "responsivePriority": 2, "targets": 3 }, // Service (with status) - high priority
            { "responsivePriority": 3, "targets": 4 } // Created - lower priority
        ],
        "language": {
            "emptyTable": "No car wash orders found"
        },
        "drawCallback": function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Make table rows clickable
            $('#allOrdersTable tbody tr').off('click.carwash').on('click.carwash', function(e) {
                // Don't trigger row click if clicking on action buttons or links
                if ($(e.target).closest('a, button').length > 0) {
                    return;
                }
                
                var data = allOrdersTable.row(this).data();
                if (data && data.id) {
                    window.location.href = '<?= base_url('car_wash/view/') ?>' + data.id;
                }
            });
            
            // Add cursor pointer to rows
            $('#allOrdersTable tbody tr').css('cursor', 'pointer');
        }
    });
    
    // Refresh button functionality
    $('#refreshAllOrdersTable').on('click', function() {
        allOrdersTable.ajax.reload();
    });
    
    // Event handlers for action buttons
    $(document).on('click', '.edit-carwash-order-btn', function(e) {
        e.preventDefault();
        var orderId = $(this).data('id');
        window.editCarWashOrder(orderId);
    });
    
    $(document).on('click', '.delete-carwash-order-btn', function(e) {
        e.preventDefault();
        var orderId = $(this).data('id');
        window.deleteCarWashOrder(orderId);
    });
    
    // Initialize icon tooltips after table load
    setTimeout(() => {
        initializeIconTooltips();
    }, 1000);
    
    // Setup tooltip event handlers for comments and notes
    $(document).on('mouseenter', '.comments-icon', function() {
        const $icon = $(this);
        const orderId = $icon.data('order-id');
        
        // Hide all other tooltips first
        hideAllTooltips();
        
        // Load tooltip for this icon
        loadCommentsTooltip(this, orderId);
    });
    
    $(document).on('mouseleave', '.comments-icon', function() {
        const $icon = $(this);
        // Use a small delay to prevent flickering
        setTimeout(() => {
            if (!$icon.is(':hover')) {
                $icon.tooltip('hide');
            }
        }, 300);
    });
    
    $(document).on('mouseenter', '.notes-icon', function() {
        const $icon = $(this);
        const orderId = $icon.data('order-id');
        
        // Hide all other tooltips first
        hideAllTooltips();
        
        // Load tooltip for this icon
        loadNotesTooltip(this, orderId);
    });
    
    $(document).on('mouseleave', '.notes-icon', function() {
        const $icon = $(this);
        // Use a small delay to prevent flickering
        setTimeout(() => {
            if (!$icon.is(':hover')) {
                $icon.tooltip('hide');
            }
        }, 300);
    });
    
    // Hide tooltips when clicking anywhere else
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.comments-icon, .notes-icon').length) {
            hideAllTooltips();
        }
    });
});

// Function to initialize icon tooltips
function initializeIconTooltips() {
    // Initialize tooltips for comments and notes icons
    $('.comments-icon, .notes-icon').tooltip({
        trigger: 'manual',
        html: true,
        placement: 'top',
        container: 'body'
    });
}

// Function to hide all tooltips
function hideAllTooltips() {
    $('.comments-icon, .notes-icon').each(function() {
        $(this).tooltip('hide');
    });
}

// Global function to handle comments tooltips
function loadCommentsTooltip(element, orderId) {
    const $icon = $(element);
    
    if (!orderId) return;
    
    // Prevent multiple requests
    if ($icon.data('loading') === true) return;
    $icon.data('loading', true);
    
    // Dispose any existing tooltip
    $icon.tooltip('dispose');
    
    // Show loading tooltip
    $icon.tooltip({
        title: 'Loading comments...',
        placement: 'top',
        trigger: 'manual',
        html: false
    }).tooltip('show');
    
    // Fetch comments preview
    const fetchUrl = '<?= base_url() ?>car_wash/getCommentsPreview/' + orderId;
    
    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let tooltipContent = '<div class="comment-preview-tooltip">';
                
                if (data.preview_comments && data.preview_comments.length > 0) {
                    data.preview_comments.forEach(comment => {
                        tooltipContent += '<div class="comment-preview-item">';
                        tooltipContent += '<div class="comment-preview-author">' + (comment.user_name || 'Unknown User') + '</div>';
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
                    placement: 'top',
                    trigger: 'manual',
                    container: 'body',
                    customClass: 'comments-preview-tooltip'
                });
                
                // Show the new tooltip
                $icon.tooltip('show');
                
            } else {
                $icon.tooltip('dispose');
                $icon.tooltip({
                    title: data.message || 'Error loading comments',
                    placement: 'top',
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
}

// Global function to handle notes tooltips
function loadNotesTooltip(element, orderId) {
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
    const fetchUrl = '<?= base_url() ?>car_wash/getNotesPreview/' + orderId;
    
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
}

// Edit and delete functions are now handled globally in index.php
</script>

<style>
/* Sales Orders table styling */
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

#allOrdersTable {
    width: 100% !important;
}

#allOrdersTable_wrapper {
    width: 100% !important;
}

#allOrdersTable thead th {
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
    justify-content: flex-start !important;
}

.vstack {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    justify-content: flex-start !important;
}

/* Center table headers */
#allOrdersTable thead th {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Center table data cells */
#allOrdersTable tbody td {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Left align only the first column (Order #) for better readability */
#allOrdersTable thead th:first-child,
#allOrdersTable tbody td:first-child {
    text-align: left !important;
}

/* Action buttons styling */
.action-buttons {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 0.5rem !important;
}

.action-buttons a {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 6px !important;
    transition: all 0.15s ease-in-out !important;
}

.action-buttons a:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

/* Actions column - always visible */
.actions-column {
    min-width: 120px !important;
    width: 120px !important;
    max-width: 120px !important;
}

/* Important columns - always visible */
#allOrdersTable th:nth-child(1), #allOrdersTable td:nth-child(1) { /* Order # */
    min-width: 100px !important;
}

#allOrdersTable th:nth-child(3), #allOrdersTable td:nth-child(3) { /* Tag/Stock */
    min-width: 80px !important;
}

#allOrdersTable th:nth-child(5), #allOrdersTable td:nth-child(5) { /* Vehicle */
    min-width: 150px !important;
}

/* Responsive table adjustments */
@media (max-width: 768px) {
    .actions-column {
        min-width: 100px !important;
        width: 100px !important;
    }
    
    .action-buttons {
        gap: 0.25rem !important;
    }
    
    .action-buttons a {
        width: 28px !important;
        height: 28px !important;
        font-size: 0.875rem !important;
    }
    
    /* Adjust important columns for tablet */
    #allOrdersTable th:nth-child(1), #allOrdersTable td:nth-child(1) { /* Order # */
        min-width: 90px !important;
    }
    
    #allOrdersTable th:nth-child(3), #allOrdersTable td:nth-child(3) { /* Tag/Stock */
        min-width: 70px !important;
    }
    
    #allOrdersTable th:nth-child(5), #allOrdersTable td:nth-child(5) { /* Vehicle */
        min-width: 120px !important;
    }
}

@media (max-width: 576px) {
    .actions-column {
        min-width: 90px !important;
        width: 90px !important;
    }
    
    .action-buttons a {
        width: 26px !important;
        height: 26px !important;
        font-size: 0.8rem !important;
    }
    
    /* Adjust important columns for mobile */
    #allOrdersTable th:nth-child(1), #allOrdersTable td:nth-child(1) { /* Order # */
        min-width: 80px !important;
        font-size: 0.875rem !important;
    }
    
    #allOrdersTable th:nth-child(3), #allOrdersTable td:nth-child(3) { /* Tag/Stock */
        min-width: 60px !important;
        font-size: 0.875rem !important;
    }
    
    #allOrdersTable th:nth-child(5), #allOrdersTable td:nth-child(5) { /* Vehicle */
        min-width: 100px !important;
        font-size: 0.875rem !important;
    }
    
    /* Make text more compact on mobile */
    #allOrdersTable {
        font-size: 0.875rem !important;
    }
}

/* Force actions column to never hide */
#allOrdersTable th.actions-column,
#allOrdersTable td.actions-column {
    display: table-cell !important;
    visibility: visible !important;
}

/* Icon styles for comments and notes */
.comments-icon, .notes-icon {
    display: inline-flex !important;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    margin-right: 0.25rem;
    visibility: visible !important;
    opacity: 1 !important;
}

.comments-icon:hover, .notes-icon:hover {
    background: rgba(13, 110, 253, 0.2);
    color: #0d6efd;
    text-decoration: none;
}

.comments-icon i, .notes-icon i {
    font-size: 0.875rem;
}

.comment-count, .note-count {
    font-weight: 600;
    min-width: 1rem;
    text-align: center;
}

/* Tooltip styles */
.tooltip.comments-preview-tooltip .tooltip-inner,
.tooltip.notes-preview-tooltip .tooltip-inner {
    max-width: 300px;
    padding: 0.75rem;
    background: #ffffff;
    color: #374151;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    font-size: 0.875rem;
    text-align: left;
}

.comment-preview-tooltip, .note-preview-tooltip {
    max-height: 200px;
    overflow-y: auto;
}

.comment-preview-item, .note-preview-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.comment-preview-item:last-child, .note-preview-item:last-child {
    border-bottom: none;
}

.comment-preview-author, .note-preview-author {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.comment-preview-text, .note-preview-text {
    color: #4b5563;
    font-size: 0.8rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.comment-preview-time, .note-preview-time {
    color: #9ca3af;
    font-size: 0.7rem;
}

/* Icons container styles */
.icons-container {
    display: flex !important;
    gap: 0.25rem !important;
    margin-top: 0.25rem !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Debug styles for icons */
.comments-icon {
    background: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
}

.notes-icon {
    background: rgba(108, 117, 125, 0.1) !important;
    color: #6c757d !important;
}
</style> 