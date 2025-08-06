<?php include(__DIR__ . '/shared_styles.php'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('App.week_view') ?> <span id="weekOrderCount"></span> - Week of <?= date('M j, Y', strtotime('monday this week')) ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshWeekOrders" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="week-service-orders-table" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
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
function initWeekServiceOrdersTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initWeekServiceOrdersTable, 100);
        return;
    }
    
    // Verificar si ya está inicializada
    if (window.weekServiceOrdersTable && $.fn.DataTable.isDataTable('#week-service-orders-table')) {
        return;
    }
    
    // Destruir tabla existente si existe
    if ($.fn.DataTable.isDataTable('#week-service-orders-table')) {
        $('#week-service-orders-table').DataTable().destroy();
    }
    
    window.weekServiceOrdersTable = $('#week-service-orders-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('service_orders/get-week-orders') ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            },
            error: function(xhr, error, code) {
                console.error('Error loading week service orders:', error);
            }
        },
        columns: window.ServiceOrdersColumnHelpers.generateStandardColumns('<?= base_url() ?>', 'general'),
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
            search: "Search this week's orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ orders this week",
            infoEmpty: "No orders for this week",
            infoFiltered: "(filtered from _MAX_ total week orders)",
            emptyTable: "No service orders scheduled for this week",
            zeroRecords: "No matching week orders found",
            processing: "Loading week orders..."
        },
        drawCallback: function(settings) {
            // Standard callback
            window.ServiceOrdersColumnHelpers.standardDrawCallback();
            
            // Update order count in title
            const api = this.api();
            const info = api.page.info();
            updateWeekOrderCount(info.recordsTotal);
        }
    });
    
    $('#refreshWeekOrders').on('click', function() {
        if (window.weekServiceOrdersTable) {
            window.weekServiceOrdersTable.ajax.reload();
        }
    });

    // Function to update the order count in title
    function updateWeekOrderCount(recordsFiltered) {
        const countElement = document.getElementById('weekOrderCount');
        const badgeElement = document.getElementById('weekOrdersBadge');
        
        if (countElement) {
            if (recordsFiltered > 0) {
                countElement.textContent = `(${recordsFiltered})`;
                countElement.style.color = '#17a2b8'; // Info color for week view
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
}

// Event handlers are managed globally in index.php
</script> 

<style>
/* Order count styling in title */
#weekOrderCount {
    font-weight: 600;
    margin-left: 0.25rem;
    transition: color 0.3s ease;
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
