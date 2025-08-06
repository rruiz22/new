<?php include(__DIR__ . '/shared_styles.php'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="list" class="icon-sm me-1"></i>
                    <?= lang('App.all_orders') ?> <span id="allOrderCount"></span>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshAllOrders" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="all-service-orders-table" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
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
function initAllServiceOrdersTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initAllServiceOrdersTable, 100);
        return;
    }
    
    // Verificar si ya está inicializada
    if (window.allServiceOrdersTable && $.fn.DataTable.isDataTable('#all-service-orders-table')) {
        return;
    }
    
    // Destruir tabla existente si existe
    if ($.fn.DataTable.isDataTable('#all-service-orders-table')) {
        $('#all-service-orders-table').DataTable().destroy();
    }
    
    window.allServiceOrdersTable = $('#all-service-orders-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('service_orders/get-all-orders') ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            },
            error: function(xhr, error, code) {
                console.error('Error loading all service orders:', error);
            }
        },
        columns: window.ServiceOrdersColumnHelpers.generateStandardColumns('<?= base_url() ?>', 'general'),
        order: [[0, 'desc']],
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
            search: "Search service orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ service orders",
            infoEmpty: "No service orders found",
            infoFiltered: "(filtered from _MAX_ total orders)",
            emptyTable: "No service orders available",
            zeroRecords: "No matching service orders found",
            processing: "Loading service orders..."
        },
        drawCallback: function(settings) {
            // Standard callback
            window.ServiceOrdersColumnHelpers.standardDrawCallback();
            
            // Update order count in title
            const api = this.api();
            const info = api.page.info();
            updateAllOrderCount(info.recordsTotal);
        }
    });
    
    $('#refreshAllOrders').on('click', function() {
        if (window.allServiceOrdersTable) {
            window.allServiceOrdersTable.ajax.reload();
        }
    });

    // Function to update the order count in title
    function updateAllOrderCount(recordsFiltered) {
        const countElement = document.getElementById('allOrderCount');
        
        if (countElement) {
            if (recordsFiltered > 0) {
                countElement.textContent = `(${recordsFiltered})`;
                countElement.style.color = '#405189'; // Primary color for all orders
            } else {
                countElement.textContent = '(0)';
                countElement.style.color = '#64748b'; // Muted color for zero count
            }
        }
    }
}

// Event handlers are managed globally in index.php
</script>

<style>
/* Order count styling in title */
#allOrderCount {
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
