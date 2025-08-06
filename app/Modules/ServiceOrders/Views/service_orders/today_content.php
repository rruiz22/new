<?php include(__DIR__ . '/shared_styles.php'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('App.today_orders') ?> <span id="todayOrderCount"></span> - <?= date('l, F j, Y') ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshTodayOrders" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <div class="btn btn-outline-info btn-sm" id="autoRefreshTimer">
                        <i data-feather="clock" class="icon-sm me-1"></i>
                        <span id="timerDisplay">60</span>s
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="today-service-orders-table" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th><?= lang('App.order_id') ?></th>
                                <th><?= lang('App.tag_ro') ?></th>
                                <th><?= lang('App.vehicle') ?></th>
                                <th><?= lang('App.due') ?></th>
                                <th><?= lang('App.status') ?></th>
                                <th><?= lang('App.actions') ?></th>
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

<script>
function initTodayServiceOrdersTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initTodayServiceOrdersTable, 100);
        return;
    }
    
    // Verificar si ya está inicializada
    if (window.todayServiceOrdersTable && $.fn.DataTable.isDataTable('#today-service-orders-table')) {
        return;
    }
    
    // Destruir tabla existente si existe
    if ($.fn.DataTable.isDataTable('#today-service-orders-table')) {
        $('#today-service-orders-table').DataTable().destroy();
    }
    
    window.todayServiceOrdersTable = $('#today-service-orders-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('service_orders/get-today-orders') ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            },
            error: function(xhr, error, code) {
                console.error('Error loading today service orders:', error);
            }
        },
        columns: window.ServiceOrdersColumnHelpers.generateStandardColumns('<?= base_url() ?>', 'today'),
        order: [[3, 'asc']],
        pageLength: 25,
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        scrollX: false,
        autoWidth: false,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            search: "Search today's orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ today's orders",
            infoEmpty: "No orders for today",
            infoFiltered: "(filtered from _MAX_ total today's orders)",
            emptyTable: "No service orders scheduled for today",
            zeroRecords: "No matching today's orders found",
            processing: "Loading today's orders..."
        },
        drawCallback: function(settings) {
            // Standard callback
            window.ServiceOrdersColumnHelpers.standardDrawCallback();
            
            // Update order count in title
            const api = this.api();
            const info = api.page.info();
            updateTodayOrderCount(info.recordsTotal);
        }
    });
    
    $('#refreshTodayOrders').on('click', function() {
        if (window.todayServiceOrdersTable) {
            window.todayServiceOrdersTable.ajax.reload();
        }
    });

    // Function to update the order count in title
    function updateTodayOrderCount(recordsFiltered) {
        const countElement = document.getElementById('todayOrderCount');
        const badgeElement = document.getElementById('todayOrdersBadge');
        
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
        if (window.todayServiceOrdersTable) {
            window.todayServiceOrdersTable.ajax.reload(null, false);
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

    // Reset timer when switching to Today's Orders tab within the application
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function(e) {
        const targetTab = $(e.target).attr('href') || $(e.target).attr('data-bs-target');
        
        // Check if switching to Today's Orders tab
        if (targetTab && (targetTab.includes('today') || targetTab.includes('Today'))) {
            if (!isPaused) {
                resetAutoRefreshTimer();
            }
        }
    });
}

// Event handlers are managed globally in index.php
</script> 

<style>
/* Order count styling in title */
#todayOrderCount {
    font-weight: 600;
    margin-left: 0.25rem;
    transition: color 0.3s ease;
}

/* Auto-refresh timer styling */
#autoRefreshTimer {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #17a2b8;
    color: #17a2b8;
}

#autoRefreshTimer:hover {
    background-color: #17a2b8;
    color: white;
}

#autoRefreshTimer.refreshing {
    border-color: #ffc107;
    color: #ffc107;
    animation: pulse 1s infinite;
}

#autoRefreshTimer.refreshing:hover {
    background-color: #ffc107;
    color: white;
}

#autoRefreshTimer.paused {
    border-color: #6c757d;
    color: #6c757d;
}

#autoRefreshTimer.paused:hover {
    background-color: #6c757d;
    color: white;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

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
</style> 
