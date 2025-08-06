<?php include(__DIR__ . '/shared_styles.php'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="clock" class="icon-sm me-1"></i>
                    <?= lang('App.pending_orders') ?> <span id="pendingOrderCount"></span>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshPendingOrders" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="pending-service-orders-table" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th><?= lang('App.order_id') ?></th>
                                <th><?= lang('App.vin') ?></th>
                                <th><?= lang('App.client') ?></th>
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

<style>
/* Estilos compactos para la tabla de pending orders */
#pending-service-orders-table {
    width: 100% !important;
    line-height: 1.1 !important;
}

#pending-service-orders-table_wrapper {
    width: 100% !important;
}

#pending-service-orders-table thead th {
    width: auto !important;
    padding: 6px 8px !important;
    font-size: 0.8rem !important;
}

#pending-service-orders-table tbody td {
    padding: 6px 8px !important;
    vertical-align: middle !important;
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

/* Estilos compactos para las celdas */
.service-order-id {
    font-weight: 600;
    color: #405189;
    font-size: 0.85rem !important;
}

.service-vin {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
    font-size: 0.75rem !important;
}

.service-client-info {
    line-height: 1.2 !important;
}

.service-client-name {
    font-weight: 500;
    color: #2c3e50;
    font-size: 0.8rem !important;
    margin-bottom: 2px;
}

.service-contact-name {
    font-size: 0.7rem !important;
    color: #74788d;
    margin-bottom: 2px;
}

.service-vehicle-info {
    font-size: 0.75rem !important;
    color: #6b7280;
    margin-bottom: 2px;
}

.service-service-info {
    font-size: 0.7rem !important;
    color: #9ca3af;
}

.service-time-badge {
    font-size: 0.7rem !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
    margin-bottom: 2px;
}

.service-time-normal {
    background-color: #e0f2fe;
    color: #0277bd;
}

.service-time-overdue {
    background-color: #ffebee;
    color: #c62828;
}

.text-success {
    color: #0ab39c !important;
}

.text-warning {
    color: #f7b84b !important;
}

.text-danger {
    color: #f06548 !important;
}

.text-muted {
    color: #74788d !important;
}

/* Badges compactos */
.badge {
    font-size: 0.75rem !important;
    padding: 3px 6px !important;
}

/* Botones compactos */
.btn-sm {
    padding: 3px 8px !important;
    font-size: 0.75rem !important;
}

@media (max-width: 768px) {
    .service-client-info {
        max-width: 150px;
    }
}

/* Order count styling in title */
#pendingOrderCount {
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

<script>
function initPendingServiceOrdersTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initPendingServiceOrdersTable, 100);
        return;
    }
    
    // Verificar si ya está inicializada
    if (window.pendingServiceOrdersTable && $.fn.DataTable.isDataTable('#pending-service-orders-table')) {
        return;
    }
    
    // Destruir tabla existente si existe
    if ($.fn.DataTable.isDataTable('#pending-service-orders-table')) {
        $('#pending-service-orders-table').DataTable().destroy();
    }
    
    window.pendingServiceOrdersTable = $('#pending-service-orders-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('service_orders/get-pending-orders') ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            },
            error: function(xhr, error, code) {
                console.error('Error loading pending service orders:', error);
            }
        },
                columns: window.ServiceOrdersColumnHelpers.generateStandardColumns('<?= base_url() ?>', 'pending'),
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
            search: "Search pending orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ pending orders",
            infoEmpty: "No pending orders found",
            infoFiltered: "(filtered from _MAX_ total pending orders)",
            emptyTable: "No pending service orders",
            zeroRecords: "No matching pending orders found",
            processing: "Loading pending orders..."
        },
        drawCallback: function(settings) {
            // Standard callback
            window.ServiceOrdersColumnHelpers.standardDrawCallback();
            
            // Update order count in title
            const api = this.api();
            const info = api.page.info();
            updatePendingOrderCount(info.recordsTotal);
        }
    });
    
    $('#refreshPendingOrders').on('click', function() {
        if (window.pendingServiceOrdersTable) {
            window.pendingServiceOrdersTable.ajax.reload();
        }
    });

    // Function to update the order count in title
    function updatePendingOrderCount(recordsFiltered) {
        const countElement = document.getElementById('pendingOrderCount');
        const badgeElement = document.getElementById('pendingOrdersBadge');
        
        if (countElement) {
            if (recordsFiltered > 0) {
                countElement.textContent = `(${recordsFiltered})`;
                countElement.style.color = '#ffc107'; // Warning color for pending orders
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

// Incluir el archivo de acciones después de que la tabla esté configurada
if (typeof generateActiveOrderActions === 'undefined') {
    const script = document.createElement('script');
    script.src = '<?= base_url('assets/js/service_orders_actions.js') ?>';
    document.head.appendChild(script);
    
    script.onload = function() {
        initServiceOrdersActions({
            baseUrl: '<?= base_url() ?>',
            csrfTokenName: '<?= csrf_token() ?>',
            csrfHash: '<?= csrf_hash() ?>'
        });
    };
}
</script> 
