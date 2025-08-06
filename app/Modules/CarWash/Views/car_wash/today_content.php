<!-- Compact New Order Form -->
<style>
/* Form refresh button styling */
#refreshFormBtn {
    transition: all 0.3s ease;
}

#refreshFormBtn:hover {
    transform: rotate(180deg);
}

#refreshFormBtn.refreshing {
    opacity: 0.6;
    pointer-events: none;
}

#refreshFormBtn.refreshing i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Form refresh indicator styling */
#form-refresh-indicator {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Form opacity during refresh */
.form-refreshing {
    transition: opacity 0.3s ease;
}
</style>

<div class="card mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="card-title mb-0">
            <i class="fas fa-car me-2"></i>Add New Car Wash Order
        </h6>
        <button type="button" class="btn btn-outline-primary btn-sm" id="refreshFormBtn" onclick="refreshFormArea()" title="Refresh form data">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
    <div class="card-body">
        <form id="quickCarWashForm">
            <!-- Hidden field for default status -->
            <input type="hidden" name="status" value="completed">
            <div class="row g-3">
                <!-- Client -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                    <select id="quick_client_id" name="client_id" class="form-select form-select-sm" required>
                        <option value="">Select Client</option>
                        <!-- Options will be loaded via AJAX -->
                    </select>
                </div>

                <!-- Tag/Stock -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_tag_stock" class="form-label fw-semibold">Tag/Stock</label>
                    <input type="text" id="quick_tag_stock" name="tag_stock" class="form-control form-control-sm" placeholder="Tag/Stock">
                </div>

                <!-- VIN -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_vin_number" class="form-label fw-semibold">VIN#</label>
                    <div class="vin-input-container position-relative">
                        <input type="text" id="quick_vin_number" name="vin_number" class="form-control form-control-sm" placeholder="<?= lang('App.vin') ?>" maxlength="17">
                        <span class="vin-status" id="vin-status"></span>
                    </div>
                    <small class="text-muted">Enter 17-character VIN for auto-decode</small>
                </div>

                <!-- Vehicle -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_vehicle" class="form-label fw-semibold">Vehicle <span class="text-danger">*</span></label>
                                            <input type="text" id="quick_vehicle" name="vehicle" class="form-control form-control-sm" placeholder="<?= lang('App.vin_vehicle_info_auto_fill') ?>" required>
                </div>

                <!-- Service -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_service_id" class="form-label fw-semibold">Service <span class="text-danger">*</span></label>
                    <select id="quick_service_id" name="service_id" class="form-select form-select-sm" required>
                        <option value="">Select Service</option>
                        <!-- Options will be loaded via AJAX -->
                    </select>
                </div>

                <!-- Priority -->
                <div class="col-sm-4 col-md-2">
                    <label for="quick_priority" class="form-label fw-semibold">Waiter?</label>
                    <div class="form-check" style="margin-top: 0.375rem;">
                        <input class="form-check-input" type="checkbox" id="quick_priority" name="priority" value="waiter">
                        <label class="form-check-label" for="quick_priority">
                            <small>Yes</small>
                        </label>
                    </div>
                </div>

                                <!-- Action Buttons -->
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearForm()">
                            <i class="fas fa-eraser me-1"></i>Clear
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i>Save Order
    </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                                                    <i data-feather="calendar" class="icon-sm me-1"></i>
                                          Today's Orders <span id="carwashOrderCount"></span> - <?= date('l, F j, Y') ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshCarwashTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        Refresh
                    </button>
                    <div class="btn btn-outline-info btn-sm" id="autoRefreshTimer">
                        <i data-feather="clock" class="icon-sm me-1"></i>
                        <span id="timerDisplay">60</span>s
                    </div>
                </div>
            </div>

            <div class="card-body">
<div class="table-responsive">
                    <table id="todayTable" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
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
    // Load form data
    loadFormData();
    
    // Initialize VIN decoding functionality
    setupVINDecoding();
    
    // Initialize recent duplicate validation for quick form
    setupQuickFormRecentDuplicateValidation();
    
    // Focus on first field after a short delay to allow page to load
    setTimeout(function() {
        $('#quick_client_id').focus();
    }, 500);
    
    // Initialize DataTable
    var table = $('#todayTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('car_wash/getTodayOrders') ?>",
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
                $('#carwashOrderCount').text('(' + json.data.length + ')');
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
        "order": [[4, "desc"]], // Order by created timestamp
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
            
            // Initialize icon tooltips
            initializeIconTooltips();
            
            // Make table rows clickable
            $('#todayTable tbody tr').off('click.carwash').on('click.carwash', function(e) {
                // Don't trigger row click if clicking on action buttons or links
                if ($(e.target).closest('a, button').length > 0) {
                    return;
                }
                
                var data = table.row(this).data();
                if (data && data.id) {
                    window.location.href = '<?= base_url('car_wash/view/') ?>' + data.id;
                }
            });
            
            // Add cursor pointer to rows
            $('#todayTable tbody tr').css('cursor', 'pointer');
        }
    });
    
    // Refresh button functionality
    $('#refreshCarwashTable').on('click', function() {
        table.ajax.reload(null, false);
        showToast('success', 'Table refreshed successfully');
        // Reset timer when manually refreshed
        resetAutoRefreshTimer();
    });
    
    // Auto-refresh timer variables
    let autoRefreshInterval;
    let countdownInterval;
    let timeRemaining = 60;
    let isPaused = false;

    // Auto-refresh timer functions
    function startAutoRefreshTimer() {
        if (isPaused) return;
        
        timeRemaining = 60;
        updateTimerDisplay();
        
        countdownInterval = setInterval(function() {
            if (isPaused) return;
            
            timeRemaining--;
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                refreshTableAndResetTimer();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        $('#timerDisplay').text(timeRemaining);
        
        // Change color based on state
        const timerElement = $('#autoRefreshTimer');
        timerElement.removeClass('refreshing paused');
        
        if (isPaused) {
            timerElement.addClass('paused');
        } else if (timeRemaining <= 10) {
            timerElement.addClass('refreshing');
        }
    }

    function pauseResumeTimer() {
        isPaused = !isPaused;
        
        if (isPaused) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            updateTimerDisplay();
        } else {
            startAutoRefreshTimer();
        }
    }

    // Click handler for timer
    $('#autoRefreshTimer').on('click', function() {
        pauseResumeTimer();
    });

    function refreshTableAndResetTimer() {
        if (table) {
            table.ajax.reload(null, false);
        }
        resetAutoRefreshTimer();
    }

    function resetAutoRefreshTimer() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        $('#autoRefreshTimer').removeClass('refreshing');
        startAutoRefreshTimer();
    }

    // Start auto-refresh timer
    function initializeAutoRefresh() {
        startAutoRefreshTimer();
    }

    // Initialize auto-refresh timer after DataTable is ready
    setTimeout(() => {
        initializeAutoRefresh();
    }, 1000);

    // Pause/Resume timer based on page visibility
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Pause timer when page is not visible (but remember if it was manually paused)
            if (countdownInterval && !isPaused) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        } else {
            // Reset and restart timer when page becomes visible (only if not manually paused)
            if (!isPaused) {
                resetAutoRefreshTimer();
            }
        }
    });

    // Reset timer when switching to CarWash tab within the application
    // Listen for tab switching events (Bootstrap tab events)
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function(e) {
        const targetTab = $(e.target).attr('href') || $(e.target).attr('data-bs-target');
        
        // Check if switching to CarWash tab
        if (targetTab && (targetTab.includes('today') || targetTab.includes('carwash'))) {
            if (!isPaused) {
                resetAutoRefreshTimer();
            }
        }
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

// Clear form
function clearForm() {
    $('#quickCarWashForm')[0].reset();
    $('#quick_client_id').focus();
    clearValidationErrors();
    
    // Clear duplicate warnings
    hideQuickFormRecentDuplicateWarning('tag_stock');
    hideQuickFormRecentDuplicateWarning('vin_number');
    
    // Clear VIN status
    clearVINStatus();
    
    // Clear vehicle field styling
    clearVehicleFieldStyling();
}

// Clear validation errors
function clearValidationErrors() {
    // Remove validation classes and error messages
    $('#quickCarWashForm .form-control, #quickCarWashForm .form-select').removeClass('is-invalid');
    $('#quickCarWashForm .invalid-feedback').remove();
    $('#quickCarWashForm .text-danger').remove();
    
    // Clear duplicate warnings
    hideQuickFormRecentDuplicateWarning('tag_stock');
    hideQuickFormRecentDuplicateWarning('vin_number');
    
    // Clear VIN status
    clearVINStatus();
    
    // Clear vehicle field styling
    clearVehicleFieldStyling();
}

// Clear vehicle field styling (green/yellow background from VIN decoding)
function clearVehicleFieldStyling() {
    const vehicleInput = document.getElementById('quick_vehicle');
    if (vehicleInput) {
        // Remove VIN decoded class
        vehicleInput.classList.remove('vin-decoded');
        
        // Clear background and border styling (covers both green and yellow backgrounds)
        vehicleInput.style.backgroundColor = '';
        vehicleInput.style.borderColor = '';
        
        console.log('CarWash: Vehicle field styling cleared');
    }
}

// Show field validation error
function showFieldValidationError(field, message) {
    // Map backend field names to frontend field IDs
    var fieldMappings = {
        'client_id': 'quick_client_id',
        'tag_stock': 'quick_tag_stock',
        'vin_number': 'quick_vin_number',
        'vehicle': 'quick_vehicle',
        'service_id': 'quick_service_id'
    };
    
    var fieldId = fieldMappings[field] || field;
    var fieldElement = $('#' + fieldId);
    
    if (fieldElement.length) {
        // Add invalid class
        fieldElement.addClass('is-invalid');
        
        // Remove any existing error message
        fieldElement.siblings('.invalid-feedback').remove();
        
        // Add error message
        fieldElement.after('<div class="invalid-feedback">' + message + '</div>');
    }
}

// Toast function using SweetAlert
function showToast(type, message) {
    if (typeof Swal !== 'undefined') {
        const config = {
            html: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        };

        // Set icon and title based on type
        switch(type) {
            case 'success':
                config.icon = 'success';
                config.title = 'Success!';
                break;
            case 'error':
                config.icon = 'error';
                config.title = 'Error!';
                break;
            case 'warning':
                config.icon = 'warning';
                config.title = 'Warning!';
                break;
            case 'info':
                config.icon = 'info';
                config.title = 'Info';
                break;
            default:
                config.icon = 'info';
                config.title = 'Notification';
        }

        Swal.fire(config);
    } else {
        // Fallback to alert if SweetAlert is not available
        alert(`${type.toUpperCase()}: ${message.replace(/<[^>]*>/g, '')}`);
    }
}


// Load form data
function loadFormData() {
    $.get('<?= base_url('car_wash/getFormData') ?>', function(response) {
        if (response.success) {
            // Load clients
            var clientSelect = $('#quick_client_id');
            clientSelect.html('<option value="">Select Client</option>');
            
            if (response.clients && Array.isArray(response.clients)) {
                response.clients.forEach(function(client) {
                    clientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                });
            }
            
            // Load services
            var serviceSelect = $('#quick_service_id');
            serviceSelect.html('<option value="">Select Service</option>');
            
            if (response.services && Array.isArray(response.services)) {
                response.services.forEach(function(service) {
                    var price = service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '';
                    serviceSelect.append('<option value="' + service.id + '">' + service.name + price + '</option>');
                });
            }
            
            console.log('Form data loaded successfully');
            console.log('Clients:', response.clients.length);
            console.log('Services:', response.services.length);
        } else {
            console.error('Failed to load form data');
            $('#quick_client_id').html('<option value="">Error loading clients</option>');
            $('#quick_service_id').html('<option value="">Error loading services</option>');
        }
    }).fail(function(xhr, status, error) {
        console.error('AJAX Error:', error);
        console.error('Status:', status);
        console.error('Response:', xhr.responseText);
        $('#quick_client_id').html('<option value="">Error loading clients</option>');
        $('#quick_service_id').html('<option value="">Error loading services</option>');
    });
}

// Refresh form area specifically after successful submission
function refreshFormArea() {
    console.log('🔄 Refreshing CarWash form area...');
    
    // Add visual feedback to show form is refreshing
    var $formCard = $('#quickCarWashForm').closest('.card');
    var $formBody = $formCard.find('.card-body');
    var $refreshBtn = $('#refreshFormBtn');
    
    // Add refreshing state to button
    $refreshBtn.addClass('refreshing');
    
    // Add subtle refreshing indicator
    $formBody.css('opacity', '0.7').addClass('form-refreshing');
    $formCard.find('.card-header').append('<span id="form-refresh-indicator" class="badge bg-primary ms-2"><i class="fas fa-sync fa-spin"></i> Refreshing...</span>');
    
    // Reload form data
    $.get('<?= base_url('car_wash/getFormData') ?>', function(response) {
        if (response.success) {
            // Load clients
            var clientSelect = $('#quick_client_id');
            clientSelect.html('<option value="">Select Client</option>');
            
            if (response.clients && Array.isArray(response.clients)) {
                response.clients.forEach(function(client) {
                    clientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                });
            }
            
            // Load services  
            var serviceSelect = $('#quick_service_id');
            serviceSelect.html('<option value="">Select Service</option>');
            
            if (response.services && Array.isArray(response.services)) {
                response.services.forEach(function(service) {
                    var price = service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '';
                    serviceSelect.append('<option value="' + service.id + '">' + service.name + price + '</option>');
                });
            }
            
            // Clear the form completely
            clearForm();
            clearValidationErrors();
            
            // Clear duplicate warnings
            hideQuickFormRecentDuplicateWarning('tag_stock');
            hideQuickFormRecentDuplicateWarning('vin_number');
            
            // Clear VIN status
            clearVINStatus();
            
            // Clear vehicle field styling
            clearVehicleFieldStyling();
            
            // Remove visual feedback
            $formBody.css('opacity', '1').removeClass('form-refreshing');
            $('#form-refresh-indicator').remove();
            $refreshBtn.removeClass('refreshing');
            
            // Focus on first field
            setTimeout(function() {
                $('#quick_client_id').focus();
            }, 100);
            
            console.log('✅ Form area refreshed successfully');
            console.log('Clients reloaded:', response.clients.length);
            console.log('Services reloaded:', response.services.length);
            
        } else {
            console.error('Failed to refresh form data');
            $('#quick_client_id').html('<option value="">Error loading clients</option>');
            $('#quick_service_id').html('<option value="">Error loading services</option>');
            
            // Clear validation messages even on error
            clearValidationErrors();
            hideQuickFormRecentDuplicateWarning('tag_stock');
            hideQuickFormRecentDuplicateWarning('vin_number');
            clearVINStatus();
            clearVehicleFieldStyling();
            
            // Remove visual feedback
            $formBody.css('opacity', '1').removeClass('form-refreshing');
            $('#form-refresh-indicator').remove();
            $refreshBtn.removeClass('refreshing');
        }
    }).fail(function(xhr, status, error) {
        console.error('AJAX Error refreshing form:', error);
        $('#quick_client_id').html('<option value="">Error loading clients</option>');
        $('#quick_service_id').html('<option value="">Error loading services</option>');
        
        // Clear validation messages even on network error
        clearValidationErrors();
        hideQuickFormRecentDuplicateWarning('tag_stock');
        hideQuickFormRecentDuplicateWarning('vin_number');
        clearVINStatus();
        clearVehicleFieldStyling();
        
        // Remove visual feedback
        $formBody.css('opacity', '1').removeClass('form-refreshing');
        $('#form-refresh-indicator').remove();
        $refreshBtn.removeClass('refreshing');
    });
}

// Handle form submission
$('#quickCarWashForm').on('submit', function(e) {
    e.preventDefault();
    
    // Clear previous validation errors
    clearValidationErrors();
    
    var formData = {
        client_id: $('#quick_client_id').val(),
        tag_stock: $('#quick_tag_stock').val(),
        vin_number: $('#quick_vin_number').val(),
        vehicle: $('#quick_vehicle').val(),
        service_id: $('#quick_service_id').val(),
        priority: $('#quick_priority').is(':checked') ? 'waiter' : 'normal',
        status: 'completed' // Default status
    };
    
    // Basic validation
    if (!formData.client_id || !formData.vehicle || !formData.service_id) {
        showToast('error', 'Please fill in all required fields');
        return;
    }
    
    // Disable submit button
    var submitBtn = $(this).find('button[type="submit"]');
    var originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');
    
    // Submit form
    $.post('<?= base_url('car_wash/store') ?>', formData, function(response) {
                 if (response.success) {
            showToast('success', 'Order created successfully!');
             clearForm();
            clearValidationErrors(); // Clear any validation errors
            // Refresh form data and table
            refreshFormArea();
             $('#todayTable').DataTable().ajax.reload();
         } else {
            var errorMessage = response.message || 'An error occurred';
            
            // Handle individual field validation errors
            if (response.errors) {
                $.each(response.errors, function(field, message) {
                    showFieldValidationError(field, message);
                });
                
                // Also show in toast for user feedback
                errorMessage += '<br><ul>';
                $.each(response.errors, function(field, message) {
                    errorMessage += '<li>' + message + '</li>';
                });
                errorMessage += '</ul>';
            }
            showToast('error', errorMessage);
        }
    }, 'json').fail(function() {
        showToast('error', 'An error occurred while saving the order');
    }).always(function() {
        // Re-enable submit button
        submitBtn.prop('disabled', false).html(originalText);
    });
    
    // Clear validation errors when user starts typing
    $('#quickCarWashForm .form-control, #quickCarWashForm .form-select').on('input change', function() {
        var $field = $(this);
        $field.removeClass('is-invalid');
        $field.siblings('.invalid-feedback').remove();
    });
    
    // Clear validation errors when user focuses on a field
    $('#quickCarWashForm .form-control, #quickCarWashForm .form-select').on('focus', function() {
        var $field = $(this);
        $field.removeClass('is-invalid');
        $field.siblings('.invalid-feedback').remove();
    });
});

// VIN Decoding Functions
// VIN Decoder translations - Safe global scope for CarWash Today
window.CarWashTodayVinTranslations = window.CarWashTodayVinTranslations || {
    loading: '<?= lang('App.vin_loading') ?>',
    onlyAlphanumeric: '<?= lang('App.vin_only_alphanumeric') ?>',
    cannotExceed17: '<?= lang('App.vin_cannot_exceed_17') ?>',
    invalidFormat: '<?= lang('App.vin_invalid_format') ?>',
    validNoInfo: '<?= lang('App.vin_valid_no_info') ?>',
    decodedNoData: '<?= lang('App.vin_decoded_no_data') ?>',
    unableToDecode: '<?= lang('App.vin_unable_to_decode') ?>',
    decodingFailed: '<?= lang('App.vin_decoding_failed') ?>',
    partial: '<?= lang('App.vin_partial') ?>',
    characters: '<?= lang('App.vin_characters') ?>',
    suspiciousPatterns: '<?= lang('App.vin_suspicious_patterns') ?>',
    invalidCheckDigit: '<?= lang('App.vin_invalid_check_digit') ?>'
};

function setupVINDecoding() {
    const vinInput = document.getElementById('quick_vin_number');
    const vehicleInput = document.getElementById('quick_vehicle');
    const vinStatus = document.getElementById('vin-status');
    
    if (!vinInput || !vehicleInput) {
        console.log('CarWash: VIN or Vehicle input not found, skipping VIN decoding setup');
        return;
    }
    
    console.log('CarWash: Setting up VIN decoding functionality');
    
    // Add VIN input event listener
    vinInput.addEventListener('input', function(e) {
        const vin = e.target.value.toUpperCase().trim();
        
        // Update input value to uppercase
        e.target.value = vin;
        
        // Clear previous status
        clearVINStatus();
        
        // Only validate alphanumeric characters
        const validVin = vin.replace(/[^A-Z0-9]/g, '');
        if (validVin !== vin) {
            e.target.value = validVin;
            showVINStatus('warning', window.CarWashTodayVinTranslations.onlyAlphanumeric);
            return;
        }
        
        // Clear vehicle field when VIN is modified/reduced
        if (vin.length < 17) {
            clearVehicleField();
        }
        
        // Check VIN length and decode accordingly
        if (vin.length === 17) {
            // Complete VIN - attempt full decoding
            showVINStatus('loading', window.CarWashTodayVinTranslations.loading);
            decodeVIN(vin);
        } else if (vin.length >= 10 && vin.length < 17) {
            // Partial VIN with enough info for basic decoding
            showVINStatus('info', `${vin.length}/17 ${window.CarWashTodayVinTranslations.characters}`);
            decodePartialVIN(vin);
        } else if (vin.length > 0 && vin.length < 10) {
            // Too short for any decoding
            showVINStatus('info', `${vin.length}/17 ${window.CarWashTodayVinTranslations.characters}`);
        } else if (vin.length > 17) {
            // Too long
            e.target.value = vin.substring(0, 17);
            showVINStatus('error', window.CarWashTodayVinTranslations.cannotExceed17);
        } else if (vin.length === 0) {
            // Empty VIN - clear everything
            clearVINStatus();
        }
    });
    
    // Add VIN validation styles
    addVINStyles();
    
    console.log('CarWash: VIN decoding setup complete');
}

function decodeVIN(vin) {
    console.log('CarWash: Decoding VIN with NHTSA API:', vin);
    
    // Basic VIN validation
    if (!isValidVIN(vin)) {
        let errorMessage = window.CarWashTodayVinTranslations.invalidFormat;
        if (window.vinValidationError === 'suspicious_patterns') {
            errorMessage = window.CarWashTodayVinTranslations.suspiciousPatterns;
        } else if (window.vinValidationError === 'invalid_check_digit') {
            errorMessage = window.CarWashTodayVinTranslations.invalidCheckDigit;
        }
        showVINStatus('error', errorMessage);
        return;
    }
    
    // Show loading status
    showVINStatus('loading', window.CarWashTodayVinTranslations.loading);
    
    // Call NHTSA vPIC API
    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;
    
    fetch(nhtsa_url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('CarWash: NHTSA API response status:', response.status);
        if (!response.ok) {
            throw new Error(`NHTSA API Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('CarWash: NHTSA API response data:', data);
        
        if (data && data.Results && data.Results.length > 0) {
            const vehicleData = data.Results[0];
            console.log('CarWash: Vehicle data from NHTSA:', vehicleData);
            
            // Build comprehensive vehicle string
            const vehicleString = buildVehicleString(vehicleData);
            
            if (vehicleString && vehicleString.trim() !== '') {
                // Update vehicle field
                const vehicleInput = document.getElementById('quick_vehicle');
                if (vehicleInput) {
                    vehicleInput.value = vehicleString;
                    vehicleInput.classList.add('vin-decoded');
                    
                    // Add temporary visual indicator to vehicle field
                    vehicleInput.style.backgroundColor = '#d1e7dd';
                    vehicleInput.style.borderColor = '#198754';
                    
                    // Clear VIN status after successful decoding
                    setTimeout(() => {
                        clearVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    
                    console.log('CarWash: Vehicle field updated with NHTSA data:', vehicleString);
                } else {
                    console.error('CarWash: Vehicle input field not found');
                }
            } else {
                // No vehicle info found
                showVINStatus('warning', window.CarWashTodayVinTranslations.validNoInfo);
                console.log('CarWash: No vehicle information found in NHTSA response');
            }
        } else {
            console.warn('CarWash: No results found in NHTSA response');
            showVINStatus('warning', window.CarWashTodayVinTranslations.decodedNoData);
        }
    })
    .catch(error => {
        console.error('CarWash: NHTSA API error:', error);
        
        // Fallback to basic decoding if NHTSA API fails
        console.log('CarWash: Falling back to basic VIN decoding');
        showVINStatus('loading', window.CarWashTodayVinTranslations.loading);
        
        try {
            const basicInfo = decodeVINBasic(vin);
            
            if (basicInfo.year || basicInfo.make) {
                const vehicleParts = [];
                if (basicInfo.year) vehicleParts.push(basicInfo.year);
                if (basicInfo.make) vehicleParts.push(basicInfo.make);
                
                const vehicleString = vehicleParts.join(' ');
                
                const vehicleInput = document.getElementById('quick_vehicle');
                if (vehicleInput) {
                    vehicleInput.value = vehicleString;
                    vehicleInput.classList.add('vin-decoded');
                    
                    // Add temporary visual indicator to vehicle field (fallback)
                    vehicleInput.style.backgroundColor = '#fff3cd';
                    vehicleInput.style.borderColor = '#fd7e14';
                    
                    setTimeout(() => {
                        clearVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    console.log('CarWash: Fallback decoding successful:', vehicleString);
                }
            } else {
                showVINStatus('error', window.CarWashTodayVinTranslations.unableToDecode);
            }
        } catch (fallbackError) {
            console.error('CarWash: Fallback decoding also failed:', fallbackError);
            showVINStatus('error', window.CarWashTodayVinTranslations.decodingFailed);
        }
    });
}

function buildVehicleString(nhtsa_data) {
    // Build simplified vehicle string from NHTSA data
    // Target format: "2017 ACURA MDX (Tech) (6 cyl)"
    
    const parts = [];
    
    // 1. Model Year
    if (nhtsa_data.ModelYear && nhtsa_data.ModelYear !== '') {
        parts.push(nhtsa_data.ModelYear);
    }
    
    // 2. Make (Manufacturer)
    if (nhtsa_data.Make && nhtsa_data.Make !== '') {
        parts.push(nhtsa_data.Make.toUpperCase());
    }
    
    // 3. Model
    if (nhtsa_data.Model && nhtsa_data.Model !== '') {
        parts.push(nhtsa_data.Model.toUpperCase());
    }
    
    // 4. Series/Trim in parentheses
    if (nhtsa_data.Series && nhtsa_data.Series !== '') {
        parts.push(`(${nhtsa_data.Series})`);
    } else if (nhtsa_data.Trim && nhtsa_data.Trim !== '') {
        parts.push(`(${nhtsa_data.Trim})`);
    }
    
    // 5. Engine cylinders in parentheses
    if (nhtsa_data.EngineNumberOfCylinders && nhtsa_data.EngineNumberOfCylinders !== '') {
        parts.push(`(${nhtsa_data.EngineNumberOfCylinders} cyl)`);
    }
    
    const result = parts.join(' ').trim();
    
    console.log('CarWash: Built simplified vehicle string from NHTSA data:', {
        'ModelYear': nhtsa_data.ModelYear,
        'Make': nhtsa_data.Make,
        'Model': nhtsa_data.Model,
        'Series': nhtsa_data.Series,
        'Trim': nhtsa_data.Trim,
        'EngineNumberOfCylinders': nhtsa_data.EngineNumberOfCylinders,
        'Final String': result
    });
    
    return result;
}

function isValidVIN(vin) {
    // VIN must be 17 characters, alphanumeric, no I, O, or Q
    if (vin.length !== 17) return false;
    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) return false;
    
    // Check for suspicious patterns (too many repeated characters)
    if (hasSuspiciousPatterns(vin)) {
        console.log('CarWash: VIN rejected - suspicious patterns detected:', vin);
        window.vinValidationError = 'suspicious_patterns';
        return false;
    }
    
    // Validate check digit (9th character)
    if (!validateVINCheckDigit(vin)) {
        console.log('CarWash: VIN rejected - invalid check digit:', vin);
        window.vinValidationError = 'invalid_check_digit';
        return false;
    }
    
    window.vinValidationError = null;
    return true;
}

function hasSuspiciousPatterns(vin) {
    // Check for too many consecutive identical characters
    const consecutivePattern = /(.)\1{3,}/; // 4+ consecutive identical characters
    if (consecutivePattern.test(vin)) {
        return true;
    }
    
    // Check for too many of the same character in the entire VIN
    const charCounts = {};
    for (let char of vin) {
        charCounts[char] = (charCounts[char] || 0) + 1;
        // If any character appears more than 4 times, it's suspicious
        if (charCounts[char] > 4) {
            return true;
        }
    }
    
    return false;
}

function validateVINCheckDigit(vin) {
    // VIN check digit validation algorithm (ISO 3779)
    const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
    const values = {
        'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
        'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
        'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9,
        '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9
    };
    
    let sum = 0;
    for (let i = 0; i < 17; i++) {
        if (i === 8) continue; // Skip check digit position
        const char = vin.charAt(i);
        const value = values[char];
        if (value === undefined) return false;
        sum += value * weights[i];
    }
    
    const checkDigit = sum % 11;
    const expectedCheckChar = checkDigit === 10 ? 'X' : checkDigit.toString();
    const actualCheckChar = vin.charAt(8);
    
    return expectedCheckChar === actualCheckChar;
}

function decodeVINBasic(vin) {
    // Basic VIN decoding - extracts year, make, and some model info
    // This is a simplified decoder - for production, consider using a VIN API service
    
    const vinInfo = {
        year: null,
        make: null,
        model: null,
        trim: null
    };
    
    try {
        // Decode year (10th character)
        const yearCode = vin.charAt(9);
        vinInfo.year = decodeYearFromVIN(yearCode);
        
        // Decode manufacturer (World Manufacturer Identifier - first 3 characters)
        const wmi = vin.substring(0, 3);
        vinInfo.make = decodeMakeFromWMI(wmi);
        
        // For more detailed decoding, we would need extensive VIN databases
        // This is a basic implementation
        
    } catch (error) {
        console.error('Basic VIN decoding error:', error);
    }
    
    return vinInfo;
}

function decodeYearFromVIN(yearCode) {
    const yearCodes = {
        'A': 1980, 'B': 1981, 'C': 1982, 'D': 1983, 'E': 1984, 'F': 1985, 'G': 1986, 'H': 1987,
        'J': 1988, 'K': 1989, 'L': 1990, 'M': 1991, 'N': 1992, 'P': 1993, 'R': 1994, 'S': 1995,
        'T': 1996, 'V': 1997, 'W': 1998, 'X': 1999, 'Y': 2000, '1': 2001, '2': 2002, '3': 2003,
        '4': 2004, '5': 2005, '6': 2006, '7': 2007, '8': 2008, '9': 2009, 'A': 2010, 'B': 2011,
        'C': 2012, 'D': 2013, 'E': 2014, 'F': 2015, 'G': 2016, 'H': 2017, 'J': 2018, 'K': 2019,
        'L': 2020, 'M': 2021, 'N': 2022, 'P': 2023, 'R': 2024, 'S': 2025, 'T': 2026, 'V': 2027,
        'W': 2028, 'X': 2029, 'Y': 2030
    };
    
    return yearCodes[yearCode] || null;
}

function decodeMakeFromWMI(wmi) {
    const wmiCodes = {
        // US Manufacturers
        '1G1': 'Chevrolet', '1G6': 'Cadillac', '1GC': 'Chevrolet', '1GT': 'GMC',
        '1FA': 'Ford', '1FB': 'Ford', '1FC': 'Ford', '1FD': 'Ford', '1FE': 'Ford', '1FF': 'Ford',
        '1FG': 'Ford', '1FH': 'Ford', '1FJ': 'Ford', '1FK': 'Ford', '1FL': 'Ford', '1FM': 'Ford',
        '1FN': 'Ford', '1FP': 'Ford', '1FR': 'Ford', '1FS': 'Ford', '1FT': 'Ford', '1FU': 'Ford',
        '1FV': 'Ford', '1FW': 'Ford', '1FX': 'Ford', '1FY': 'Ford', '1FZ': 'Ford',
        '1HD': 'Harley-Davidson', '1HG': 'Honda', '1J4': 'Jeep', '1J8': 'Jeep',
        '1L1': 'Lincoln', '1LN': 'Lincoln', '1ME': 'Mercury', '1MH': 'Mercury',
        '1N4': 'Nissan', '1N6': 'Nissan', '1VW': 'Volkswagen',
        '2C3': 'Chrysler', '2C4': 'Chrysler', '2D4': 'Dodge', '2D8': 'Dodge',
        '2FA': 'Ford', '2FB': 'Ford', '2FC': 'Ford', '2FD': 'Ford', '2FE': 'Ford',
        '2G1': 'Chevrolet', '2G4': 'Pontiac', '2HG': 'Honda', '2HK': 'Honda', '2HM': 'Hyundai',
        '2T1': 'Toyota', '2T2': 'Toyota', '2T3': 'Toyota',
        '3FA': 'Ford', '3FE': 'Ford', '3G1': 'Chevrolet', '3G3': 'Oldsmobile', '3G4': 'Buick',
        '3G5': 'Buick', '3G6': 'Pontiac', '3G7': 'Pontiac', '3GN': 'Chevrolet',
        '3H1': 'Honda', '3HG': 'Honda', '3HM': 'Honda', '3N1': 'Nissan', '3VW': 'Volkswagen',
        '4F2': 'Ford', '4F4': 'Mazda', '4M2': 'Mercury', '4S3': 'Subaru', '4S4': 'Subaru',
        '4T1': 'Toyota', '4T3': 'Toyota', '4US': 'BMW',
        '5F2': 'Ford', '5FN': 'Honda', '5J6': 'Honda', '5L1': 'Lincoln', '5N1': 'Nissan',
        '5NP': 'Hyundai', '5TD': 'Toyota', '5TE': 'Toyota', '5TF': 'Toyota',
        
        // German Manufacturers
        'WAU': 'Audi', 'WA1': 'Audi', 'WBA': 'BMW', 'WBS': 'BMW', 'WBX': 'BMW',
        'WDB': 'Mercedes-Benz', 'WDD': 'Mercedes-Benz', 'WDC': 'Mercedes-Benz',
        'WP0': 'Porsche', 'WP1': 'Porsche', 'WVW': 'Volkswagen', 'WV1': 'Volkswagen', 'WV2': 'Volkswagen',
        
        // Japanese Manufacturers
        'JH4': 'Acura', 'JHM': 'Honda', 'JF1': 'Subaru', 'JF2': 'Subaru',
        'JM1': 'Mazda', 'JM3': 'Mazda', 'JN1': 'Nissan', 'JN6': 'Nissan', 'JN8': 'Nissan',
        'JT2': 'Toyota', 'JT3': 'Toyota', 'JT4': 'Toyota', 'JT6': 'Toyota', 'JT8': 'Toyota',
        'JTD': 'Toyota', 'JTE': 'Toyota', 'JTF': 'Toyota', 'JTG': 'Toyota', 'JTH': 'Lexus',
        'JTJ': 'Lexus', 'JTK': 'Lexus', 'JTL': 'Lexus', 'JTM': 'Lexus', 'JTN': 'Lexus',
        
        // Korean Manufacturers
        'KMH': 'Hyundai', 'KMJ': 'Hyundai', 'KNA': 'Kia', 'KNB': 'Kia', 'KNC': 'Kia', 'KND': 'Kia',
        'KNE': 'Kia', 'KNF': 'Kia', 'KNG': 'Kia', 'KNH': 'Kia', 'KNJ': 'Kia', 'KNK': 'Kia',
        'KNL': 'Kia', 'KNM': 'Kia',
        
        // Other
        'SAL': 'Land Rover', 'SAJ': 'Jaguar', 'SCC': 'Lotus',
        'VF1': 'Renault', 'VF3': 'Peugeot', 'VF7': 'Citroën',
        'YK1': 'Saab', 'YS3': 'Saab', 'YV1': 'Volvo', 'YV4': 'Volvo'
    };
    
    return wmiCodes[wmi] || null;
}

function showVINStatus(type, message) {
    const vinStatus = document.getElementById('vin-status');
    const vinInput = document.getElementById('quick_vin_number');
    
    // Clear previous status
    clearVINStatus();
    
    // For critical errors, show toast notification
    if (type === 'error') {
        showVINToast('error', message);
        
        // Also update input styling for visual feedback
        if (vinInput) {
            vinInput.classList.add('vin-error');
            // Clear error styling after 3 seconds
            setTimeout(() => {
                vinInput.classList.remove('vin-error');
            }, 3000);
        }
        return;
    }
    
    // For warnings about API issues, show toast
    if (type === 'warning' && (message.includes(window.CarWashTodayVinTranslations.validNoInfo) || message.includes(window.CarWashTodayVinTranslations.decodedNoData))) {
        showVINToast('warning', message);
        return;
    }
    
    // For info messages (character counter) and loading, show inline
    if (vinStatus && (type === 'info' || type === 'loading')) {
    vinStatus.textContent = message;
    vinStatus.className = `vin-status vin-status-${type}`;
    
    // Update input styling
    if (vinInput) {
        vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
        
        if (type === 'loading') {
            vinInput.classList.add('vin-decoding');
        }
    }
    
        // Auto-hide info messages
        if (type === 'info') {
        setTimeout(() => {
            clearVINStatus();
            }, 2000);
        }
    }
}

function showVINToast(type, message) {
    // Use global toast system if available
    if (typeof window.showToast === 'function') {
        window.showToast(message, type, { duration: 4000 });
    } else if (typeof showToast === 'function') {
        showToast(type, message);
    } else if (typeof Toastify !== 'undefined') {
        // Fallback to simple Toastify
        const colors = {
            success: '#28a745',
            error: '#dc3545', 
            warning: '#fd7e14',
            info: '#17a2b8'
        };
        
        Toastify({
            text: message,
            duration: 4000,
            gravity: 'top',
            position: 'right',
            backgroundColor: colors[type] || colors.info,
            close: true,
            stopOnFocus: true,
            className: 'vin-toast'
        }).showToast();
    } else {
        // Final fallback
        console.log(`VIN ${type.toUpperCase()}: ${message}`);
    }
}

function clearVINStatus() {
    const vinStatus = document.getElementById('vin-status');
    const vinInput = document.getElementById('quick_vin_number');
    
    if (vinStatus) {
        vinStatus.textContent = '';
        vinStatus.className = 'vin-status';
    }
    
    if (vinInput) {
        vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
    }
}

function clearVehicleField() {
    const vehicleInput = document.getElementById('quick_vehicle');
    
    if (vehicleInput) {
        // Only clear if the field was previously auto-filled by VIN decoder
        if (vehicleInput.classList.contains('vin-decoded')) {
            vehicleInput.value = '';
            vehicleInput.classList.remove('vin-decoded');
            vehicleInput.style.backgroundColor = '';
            vehicleInput.style.borderColor = '';
            
            console.log('CarWash: Vehicle field cleared due to VIN modification');
        }
    }
}

function decodePartialVIN(vin) {
    console.log('CarWash: Attempting partial VIN decoding:', vin);
    
    if (vin.length >= 10) {
        try {
            const basicInfo = decodeVINBasic(vin);
            
            if (basicInfo.year || basicInfo.make) {
                const vehicleParts = [];
                if (basicInfo.year) vehicleParts.push(basicInfo.year);
                if (basicInfo.make) vehicleParts.push(basicInfo.make);
                
                const vehicleString = vehicleParts.join(' ');
                
                if (vehicleString.trim() !== '') {
                    const vehicleInput = document.getElementById('quick_vehicle');
                    if (vehicleInput) {
                                        vehicleInput.value = vehicleString + ` (${window.CarWashTodayVinTranslations.partial})`;
                vehicleInput.classList.add('vin-decoded');
                        
                        // Add visual indicator for partial decoding
                        vehicleInput.style.backgroundColor = '#fff3cd';
                        vehicleInput.style.borderColor = '#fd7e14';
                        
                        console.log('CarWash: Partial VIN decoding successful:', vehicleString);
                    }
                }
            }
        } catch (error) {
            console.error('CarWash: Partial VIN decoding error:', error);
        }
    }
}

function addVINStyles() {
    // Check if styles already exist
    if (document.getElementById('vin-decoding-styles')) {
        return;
    }
    
    const style = document.createElement('style');
    style.id = 'vin-decoding-styles';
    style.textContent = `
        .vin-input-container {
            position: relative;
        }
        
        .vin-status {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            font-weight: 500;
            pointer-events: none;
            z-index: 10;
        }
        
        .vin-status-loading {
            color: #6c757d;
        }
        
        .vin-status-success {
            color: #198754;
        }
        
        .vin-status-error {
            color: #dc3545;
        }
        
        .vin-status-warning {
            color: #fd7e14;
        }
        
        .vin-status-info {
            color: #0dcaf0;
        }
        
        .vin-decoding {
            background-color: #f1f8ff !important;
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important;
            position: relative;
        }
        
        .vin-success {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
        }
        
        .vin-error {
            background-color: #f8d7da !important;
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
            animation: vinErrorPulse 0.5s ease-in-out;
        }
        
        .vin-warning {
            background-color: #fff3cd !important;
            border-color: #fd7e14 !important;
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.15) !important;
        }
        
        .vin-decoded {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
            animation: vinDecodeSuccess 0.5s ease-out;
        }
        
        @keyframes vinDecodeSuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        @keyframes vinErrorPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        .vin-decoding::after {
            content: "";
            position: absolute;
            right: 35px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #007bff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: vinSpin 1s linear infinite;
        }
        
        @keyframes vinSpin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }
        
        /* VIN Toast Styling */
        .vin-toast {
            font-family: inherit !important;
            font-size: 14px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        .vin-toast .toastify-content {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
        }
        
        .vin-toast .toastify-content::before {
            content: '⚠️';
            font-size: 16px;
            line-height: 1;
        }
        
        .vin-toast[style*="#dc3545"] .toastify-content::before {
            content: '❌';
        }
        
        .vin-toast[style*="#28a745"] .toastify-content::before {
            content: '✅';
        }
        
        .vin-toast[style*="#fd7e14"] .toastify-content::before {
            content: '⚠️';
        }
    `;
    
    document.head.appendChild(style);
}

// Recent Duplicate Validation Functions for Quick Form
function setupQuickFormRecentDuplicateValidation() {
    const tagStockInput = document.getElementById('quick_tag_stock');
    const vinInput = document.getElementById('quick_vin_number');
    
    // Setup dynamic validation for tag/stock field
    if (tagStockInput) {
        tagStockInput.addEventListener('input', function() {
            const tagValue = this.value.trim();
            // Clear existing warnings immediately
            hideQuickFormRecentDuplicateWarning('tag_stock');
            
            if (tagValue) {
                // Check for recent duplicates with debounce
                checkQuickFormRecentDuplicates('tag_stock', tagValue);
            }
        });
    }
    
    // VIN duplicate validation is integrated into the existing VIN decoding
    // We need to modify setupVINDecoding() to include duplicate checking
    if (vinInput) {
        // Add event listener for complete VIN (17 chars) to check duplicates
        vinInput.addEventListener('input', function() {
            const vin = this.value.toUpperCase().trim();
            if (vin.length === 17) {
                // Check for recent duplicates on complete VIN
                checkQuickFormRecentDuplicates('vin_number', vin);
            } else {
                // Clear duplicate warnings when VIN is not complete
                hideQuickFormRecentDuplicateWarning('vin_number');
            }
        });
    }
}

function checkQuickFormRecentDuplicates(field, value) {
    console.log(`CarWash Quick Form: checkQuickFormRecentDuplicates START for ${field}:`, value);
    
    if (!value) {
        console.log(`CarWash Quick Form: No value provided for ${field}, hiding warning`);
        hideQuickFormRecentDuplicateWarning(field);
        return;
    }

    console.log(`CarWash Quick Form: Checking ${field} for recent duplicates:`, value);

    // Clear any existing timeout for this specific field to debounce requests
    if (!window.quickCarWashDuplicateTimeouts) {
        window.quickCarWashDuplicateTimeouts = {};
    }
    
    if (window.quickCarWashDuplicateTimeouts[field]) {
        console.log(`CarWash Quick Form: Clearing existing timeout for ${field}`);
        clearTimeout(window.quickCarWashDuplicateTimeouts[field]);
    }

    // Get selected time window from the selector (default to 5 if not found)
    const duplicateCheckTimeSelector = document.getElementById('duplicateCheckTime');
    console.log(`CarWash Quick Form: duplicateCheckTime selector found for ${field}:`, !!duplicateCheckTimeSelector);
    
    const selectedMinutes = duplicateCheckTimeSelector ? duplicateCheckTimeSelector.value : '5';
    console.log(`CarWash Quick Form: Selected minutes for ${field}:`, selectedMinutes);

    // Debounce the duplicate check to avoid too many requests (separate timeout per field)
    console.log(`CarWash Quick Form: Setting timeout for ${field} (600ms debounce)`);
    window.quickCarWashDuplicateTimeouts[field] = setTimeout(() => {
        console.log(`CarWash Quick Form: TIMEOUT EXECUTED - Making duplicate check request for ${field}:`, value);
        console.log(`CarWash Quick Form: Request data for ${field}:`, {
            field: field,
            value: value,
            minutes: selectedMinutes,
            current_order_id: null
        });
        
        console.log(`CarWash Quick Form: About to make POST request for ${field}...`);
        $.post('<?= base_url('car_wash/checkRecentDuplicates') ?>', {
            field: field,
            value: value,
            minutes: selectedMinutes,
            current_order_id: null // For new orders, this is always null
        })
        .done(function(data) {
            console.log(`CarWash Quick Form: POST SUCCESS - Duplicate check response for ${field}:`, data);
            console.log(`CarWash Quick Form: Has duplicates for ${field}: ${data.has_duplicates}`);
            console.log(`CarWash Quick Form: Duplicates data for ${field}:`, data.duplicates);
            
            if (data.success && data.has_duplicates) {
                console.log(`CarWash Quick Form: Showing warning for ${field}`);
                showQuickFormRecentDuplicateWarning(field, data.duplicates);
            } else {
                console.log(`CarWash Quick Form: Hiding warning for ${field} (no duplicates or error)`);
                hideQuickFormRecentDuplicateWarning(field);
            }
        })
        .fail(function(xhr, status, error) {
            console.error(`CarWash Quick Form: POST FAILED - Error checking recent duplicates for ${field}:`, error);
            console.error(`CarWash Quick Form: XHR for ${field}:`, xhr);
            console.error(`CarWash Quick Form: Status for ${field}:`, status);
            hideQuickFormRecentDuplicateWarning(field);
        })
        .always(function() {
            // Clean up timeout reference
            if (window.quickCarWashDuplicateTimeouts && window.quickCarWashDuplicateTimeouts[field]) {
                delete window.quickCarWashDuplicateTimeouts[field];
            }
        });
    }, 600); // 600ms debounce delay for quick form
    
    console.log(`CarWash Quick Form: checkQuickFormRecentDuplicates END for ${field} - timeout set`);
}

function showQuickFormRecentDuplicateWarning(field, duplicates) {
    console.log(`CarWash Quick Form: showQuickFormRecentDuplicateWarning called for ${field}`, duplicates);
    console.log(`CarWash Quick Form: Full duplicates object:`, JSON.stringify(duplicates, null, 2));
    
    const fieldId = field === 'tag_stock' ? 'quick_tag_stock' : 'quick_vin_number';
    const inputField = document.getElementById(fieldId);
    
    console.log(`CarWash Quick Form: Field ID: ${fieldId}, Input field found:`, !!inputField);
    console.log(`CarWash Quick Form: Input field element:`, inputField);
    
    if (!inputField) {
        console.error(`CarWash Quick Form: Input field ${fieldId} not found!`);
        return;
    }

    // Remove existing warning
    hideQuickFormRecentDuplicateWarning(field);

    const fieldContainer = inputField.closest('.col-md-2, .col-md-3');
    console.log(`CarWash Quick Form: Field container found:`, !!fieldContainer);
    console.log(`CarWash Quick Form: Field container element:`, fieldContainer);
    
    if (!fieldContainer) {
        console.error(`CarWash Quick Form: Field container not found for ${fieldId}!`);
        // Try alternative selectors
        const parentDiv = inputField.parentElement;
        console.log(`CarWash Quick Form: Parent element:`, parentDiv);
        const grandParentDiv = parentDiv ? parentDiv.parentElement : null;
        console.log(`CarWash Quick Form: Grandparent element:`, grandParentDiv);
        return;
    }

    // Get selected time window for display
    const duplicateCheckTimeSelector = document.getElementById('duplicateCheckTime');
    const selectedMinutes = duplicateCheckTimeSelector ? duplicateCheckTimeSelector.value : '5';
    console.log(`CarWash Quick Form: duplicateCheckTime selector found:`, !!duplicateCheckTimeSelector);
    console.log(`CarWash Quick Form: Selected minutes:`, selectedMinutes);

    let duplicateInfo = '';
    console.log(`CarWash Quick Form: Checking duplicates[${field}]:`, duplicates[field]);
    console.log(`CarWash Quick Form: Keys in duplicates:`, Object.keys(duplicates));
    
    if (duplicates[field]) {
        const orders = duplicates[field].orders;
        const count = duplicates[field].count;

        console.log(`CarWash Quick Form: Found ${count} duplicates for ${field}:`, orders);

        // Create enhanced duplicate info with timing
        const ordersList = orders.map(order => {
            const minutesAgo = order.minutes_ago;
            const timeText = minutesAgo < 1 ? 'just now' : 
                            minutesAgo === 1 ? '1 minute ago' : 
                            `${Math.floor(minutesAgo)} minutes ago`;
            return `#${order.order_number} (${timeText})`;
        }).join(', ');

        const countText = count === 1 ? 'recent duplicate' : 'recent duplicates';
        duplicateInfo = `${count} ${countText}: ${ordersList}`;
    } else {
        console.error(`CarWash Quick Form: No duplicates found for field ${field} in response!`);
        console.error(`CarWash Quick Form: Available fields in duplicates:`, Object.keys(duplicates));
        return;
    }

    console.log(`CarWash Quick Form: Creating warning div with info:`, duplicateInfo);
    const warningDiv = document.createElement('div');
    warningDiv.id = `quick-${field}-duplicate-warning`;
    warningDiv.className = 'alert alert-warning mt-2 mb-0 p-2';
    warningDiv.style.fontSize = '0.75rem';

    const fieldLabel = field === 'tag_stock' ? 'Tag/Stock' : 'VIN';
    
    warningDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-1"></i>
        <strong>⚠️ Recent ${fieldLabel} Duplicate!</strong><br>
        <small>${duplicateInfo}</small><br>
        <small class="text-muted"><em>Used in last ${selectedMinutes} minutes. Verify not duplicate.</em></small>
    `;

    console.log(`CarWash Quick Form: Appending warning div to container`);
    console.log(`CarWash Quick Form: Warning div HTML:`, warningDiv.outerHTML);
    fieldContainer.appendChild(warningDiv);

    // Add warning styling to input
    inputField.style.borderColor = '#fd7e14';
    inputField.style.backgroundColor = '#fff3cd';
    inputField.style.boxShadow = '0 0 0 0.2rem rgba(253, 126, 20, 0.25)';
    
    console.log(`CarWash Quick Form: Warning displayed successfully for ${field}`);
    console.log(`CarWash Quick Form: Warning element now in DOM:`, document.getElementById(`quick-${field}-duplicate-warning`));
}

function hideQuickFormRecentDuplicateWarning(field) {
    const warningElement = document.getElementById(`quick-${field}-duplicate-warning`);
    if (warningElement) {
        warningElement.remove();
    }

    const fieldId = field === 'tag_stock' ? 'quick_tag_stock' : 'quick_vin_number';
    const inputField = document.getElementById(fieldId);
    if (inputField) {
        inputField.style.borderColor = '';
        inputField.style.backgroundColor = '';
        inputField.style.boxShadow = '';
    }
}

// REMOVED DUPLICATE FUNCTION - Only one setupQuickFormRecentDuplicateValidation() needed
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

#todayTable {
    width: 100% !important;
}

#todayTable_wrapper {
    width: 100% !important;
}

#todayTable thead th {
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
#todayTable thead th {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Center table data cells */
#todayTable tbody td {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Left align only the first column (Order #) for better readability */
#todayTable thead th:first-child,
#todayTable tbody td:first-child {
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
#todayTable th:nth-child(1), #todayTable td:nth-child(1) { /* Order # */
    min-width: 100px !important;
}

#todayTable th:nth-child(3), #todayTable td:nth-child(3) { /* Tag/Stock */
    min-width: 80px !important;
}

#todayTable th:nth-child(5), #todayTable td:nth-child(5) { /* Vehicle */
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
    #todayTable th:nth-child(1), #todayTable td:nth-child(1) { /* Order # */
        min-width: 90px !important;
    }
    
    #todayTable th:nth-child(3), #todayTable td:nth-child(3) { /* Tag/Stock */
        min-width: 70px !important;
    }
    
    #todayTable th:nth-child(5), #todayTable td:nth-child(5) { /* Vehicle */
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
    #todayTable th:nth-child(1), #todayTable td:nth-child(1) { /* Order # */
        min-width: 80px !important;
        font-size: 0.875rem !important;
    }
    
    #todayTable th:nth-child(3), #todayTable td:nth-child(3) { /* Tag/Stock */
        min-width: 60px !important;
        font-size: 0.875rem !important;
    }
    
    #todayTable th:nth-child(5), #todayTable td:nth-child(5) { /* Vehicle */
        min-width: 100px !important;
        font-size: 0.875rem !important;
    }
    
    /* Make text more compact on mobile */
    #todayTable {
        font-size: 0.875rem !important;
    }
}

/* Force actions column to never hide */
#todayTable th.actions-column,
#todayTable td.actions-column {
    display: table-cell !important;
    visibility: visible !important;
}

/* Timer states styling */
#autoRefreshTimer {
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}

#autoRefreshTimer:hover {
    transform: scale(1.05) !important;
}

#autoRefreshTimer.refreshing {
    background-color: #f06548 !important;
    border-color: #f06548 !important;
    color: white !important;
    animation: pulse 1s infinite !important;
}

#autoRefreshTimer.paused {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
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
</style> 