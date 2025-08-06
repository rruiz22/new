<!-- Dashboard Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border-radius: 12px; border: 1px solid #e3e6f0;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-filter me-2"></i><?= lang('App.filters') ?>
                </h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="clearDashboardFilters()">
                    <i class="fas fa-times me-1"></i><?= lang('App.clear_filters') ?>
                </button>
            </div>
            <div class="card-body">
                <form id="dashboardFiltersForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="dashboard_client_filter" class="form-label small fw-bold"><?= lang('App.client') ?></label>
                            <select id="dashboard_client_filter" name="client_filter" class="form-select form-select-sm">
                                <option value=""><?= lang('App.all_clients') ?></option>
                                <?php if (!empty($clients)): ?>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dashboard_status_filter" class="form-label small fw-bold"><?= lang('App.status') ?></label>
                            <select id="dashboard_status_filter" name="status_filter" class="form-select form-select-sm">
                                <option value=""><?= lang('App.all_status') ?></option>
                                <option value="pending"><?= lang('App.pending') ?></option>
                                <option value="confirmed"><?= lang('App.confirmed') ?></option>
                                <option value="in_progress"><?= lang('App.in_progress') ?></option>
                                <option value="completed"><?= lang('App.completed') ?></option>
                                <option value="cancelled"><?= lang('App.cancelled') ?></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dashboard_service_filter" class="form-label small fw-bold"><?= lang('App.service') ?></label>
                            <select id="dashboard_service_filter" name="service_filter" class="form-select form-select-sm">
                                <option value=""><?= lang('App.all_services') ?></option>
                                <?php if (!empty($services)): ?>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= $service['id'] ?>"><?= esc($service['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dashboard_date_range" class="form-label small fw-bold"><?= lang('App.date_range') ?></label>
                            <div class="input-group">
                                <input type="date" id="dashboard_date_from" name="date_from_filter" class="form-control form-control-sm" placeholder="<?= lang('App.from') ?>">
                                <input type="date" id="dashboard_date_to" name="date_to_filter" class="form-control form-control-sm" placeholder="<?= lang('App.to') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i><?= lang('App.apply_filters') ?>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearDashboardFilters()">
                                    <i class="fas fa-times me-1"></i><?= lang('App.clear') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Filter Status Alert -->
<?php if (!empty($has_filters)): ?>
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong><?= lang('App.filters_applied') ?>:</strong> <?= lang('App.showing_filtered_results') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Stats Cards -->
<div class="row mb-4">
    <!-- Today's Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #0066cc;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.today_orders') ?></span>
                        <h3 class="mb-0 fw-bold text-primary">
                            <span class="counter-value" data-target="<?= $stats['today'] ?? 0 ?>"><?= $stats['today'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tomorrow's Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.tomorrow_orders') ?></span>
                        <h3 class="mb-0 fw-bold text-info">
                            <span class="counter-value" data-target="<?= $stats['tomorrow'] ?? 0 ?>"><?= $stats['tomorrow'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-info">
                            <span class="avatar-title rounded-circle bg-info text-white">
                                <i class="fas fa-calendar-plus"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.pending') ?></span>
                        <h3 class="mb-0 fw-bold text-warning">
                            <span class="counter-value" data-target="<?= $stats['pending'] ?? 0 ?>"><?= $stats['pending'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-warning">
                            <span class="avatar-title rounded-circle bg-warning text-white">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #28a745;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.completed') ?></span>
                        <h3 class="mb-0 fw-bold text-success">
                            <span class="counter-value" data-target="<?= $stats['completed'] ?? 0 ?>"><?= $stats['completed'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-success">
                            <span class="avatar-title rounded-circle bg-success text-white">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- In Progress Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #6f42c1;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.in_progress') ?></span>
                        <h3 class="mb-0 fw-bold text-purple">
                            <span class="counter-value" data-target="<?= $stats['in_progress'] ?? 0 ?>"><?= $stats['in_progress'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle" style="background-color: #6f42c1;">
                            <span class="avatar-title rounded-circle text-white">
                                <i class="fas fa-cog"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #6c757d;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted small fw-bold d-block"><?= lang('App.total_orders') ?></span>
                        <h3 class="mb-0 fw-bold text-secondary">
                            <span class="counter-value" data-target="<?= $stats['total'] ?? 0 ?>"><?= $stats['total'] ?? 0 ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-secondary">
                            <span class="avatar-title rounded-circle bg-secondary text-white">
                                <i class="fas fa-list"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 small fw-bold d-block"><?= lang('App.revenue_today') ?></span>
                        <h3 class="mb-0 fw-bold text-white">
                            $<span class="counter-value" data-target="<?= $stats['revenue_today'] ?? 0 ?>"><?= number_format($stats['revenue_today'] ?? 0, 2) ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25">
                            <span class="avatar-title rounded-circle text-white">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 small fw-bold d-block"><?= lang('App.revenue_this_week') ?></span>
                        <h3 class="mb-0 fw-bold text-white">
                            $<span class="counter-value" data-target="<?= $stats['revenue_this_week'] ?? 0 ?>"><?= number_format($stats['revenue_this_week'] ?? 0, 2) ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25">
                            <span class="avatar-title rounded-circle text-white">
                                <i class="fas fa-chart-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card h-100" style="border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-white-50 small fw-bold d-block"><?= lang('App.revenue_this_month') ?></span>
                        <h3 class="mb-0 fw-bold text-white">
                            $<span class="counter-value" data-target="<?= $stats['revenue_this_month'] ?? 0 ?>"><?= number_format($stats['revenue_this_month'] ?? 0, 2) ?></span>
                        </h3>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-25">
                            <span class="avatar-title rounded-circle text-white">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Daily Orders Chart -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100" style="border-radius: 12px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fas fa-chart-line me-2 text-primary"></i><?= lang('App.daily_orders_last_7_days') ?>
                </h5>
            </div>
            <div class="card-body">
                <div id="dailyOrdersChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Orders by Status Chart -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100" style="border-radius: 12px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fas fa-chart-pie me-2 text-success"></i><?= lang('App.orders_by_status') ?>
                </h5>
            </div>
            <div class="card-body">
                <div id="ordersByStatusChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Services and Top Clients -->
<div class="row mb-4">
    <!-- Popular Services -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100" style="border-radius: 12px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fas fa-star me-2 text-warning"></i><?= lang('App.popular_services') ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($popular_services)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($popular_services as $service): ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-cog fs-4" style="color: <?= $service['color'] ?? '#007bff' ?>;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($service['service_name']) ?></h6>
                                            <small class="text-muted"><?= $service['usage_count'] ?> <?= lang('App.orders') ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary fs-6 px-3 py-2"><?= $service['usage_count'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-secondary mb-3"></i>
                        <p class="text-muted"><?= lang('App.no_data_available') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Clients -->
    <div class="col-xl-6 mb-4">
        <div class="card h-100" style="border-radius: 12px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fas fa-users me-2 text-info"></i><?= lang('App.top_clients') ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_clients)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($top_clients as $client): ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-user fs-4 text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($client['client_name']) ?></h6>
                                            <small class="text-muted"><?= $client['order_count'] ?> <?= lang('App.orders') ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-info fs-6 px-3 py-2"><?= $client['order_count'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-secondary mb-3"></i>
                        <p class="text-muted"><?= lang('App.no_data_available') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 12px;">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title fw-bold mb-0 text-dark">
                    <i class="fas fa-table me-2 text-primary"></i>
                    <?= $has_filters ? lang('App.filtered_orders') : lang('App.recent_orders') ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dashboardOrdersTable" class="table table-striped table-hover dt-responsive nowrap" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold"><?= lang('App.order_number') ?></th>
                                <th class="fw-bold"><?= lang('App.client') ?></th>
                                <th class="fw-bold"><?= lang('App.vehicle') ?></th>
                                <th class="fw-bold"><?= lang('App.service') ?></th>
                                <th class="fw-bold"><?= lang('App.date') ?></th>
                                <th class="fw-bold"><?= lang('App.status') ?></th>
                                <th class="fw-bold"><?= lang('App.priority') ?></th>
                                <th class="fw-bold"><?= lang('App.price') ?></th>
                                <th class="fw-bold"><?= lang('App.actions') ?></th>
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
$(document).ready(function() {
    // Initialize dashboard charts
    initializeDashboardCharts();
    
    // Initialize dashboard table
    initializeDashboardTable();
    
    // Handle filter form submission
    $('#dashboardFiltersForm').on('submit', function(e) {
        e.preventDefault();
        applyDashboardFilters();
    });
    
    // Handle filter changes
    $('#dashboardFiltersForm select, #dashboardFiltersForm input').on('change', function() {
        applyDashboardFilters();
    });
    
    // Counter animation
    $('.counter-value').each(function() {
        var $this = $(this);
        var target = parseInt($this.data('target'));
        var current = 0;
        var increment = target / 100;
        
        var timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (target > 0) {
                $this.text(Math.floor(current));
            }
        }, 20);
    });
});

function initializeDashboardCharts() {
    // Daily Orders Chart
    const dailyOrdersData = <?= json_encode($daily_orders) ?>;
    const dailyOrdersChart = {
        chart: {
            type: 'line',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: '<?= lang('App.orders') ?>',
            data: dailyOrdersData.map(item => item.count)
        }],
        xaxis: {
            categories: dailyOrdersData.map(item => item.formatted_date)
        },
        colors: ['#0066cc'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 6
        },
        grid: {
            show: true,
            borderColor: '#e0e6ed',
            strokeDashArray: 5
        }
    };
    
    new ApexCharts(document.querySelector("#dailyOrdersChart"), dailyOrdersChart).render();
    
    // Orders by Status Chart
    const statusData = <?= json_encode($orders_by_status) ?>;
    const statusChart = {
        chart: {
            type: 'donut',
            height: 300
        },
        series: statusData.map(item => item.count),
        labels: statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1).replace('_', ' ')),
        colors: ['#ffc107', '#28a745', '#6f42c1', '#0066cc', '#dc3545'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    
    new ApexCharts(document.querySelector("#ordersByStatusChart"), statusChart).render();
}

function initializeDashboardTable() {
    $('#dashboardOrdersTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('car_wash/getAllOrders') ?>",
            "type": "GET",
            "data": function(d) {
                // Add current filters to the request
                d.client_filter = $('#dashboard_client_filter').val();
                d.status_filter = $('#dashboard_status_filter').val();
                d.service_filter = $('#dashboard_service_filter').val();
                d.date_from_filter = $('#dashboard_date_from').val();
                d.date_to_filter = $('#dashboard_date_to').val();
            }
        },
        "columns": [
            {
                "data": "order_number",
                "render": function(data, type, row) {
                    return '<a href="<?= base_url('car_wash/view/') ?>' + row.id + '" class="text-primary fw-bold">' + data + '</a>';
                }
            },
            {
                "data": "client_name",
                "render": function(data, type, row) {
                    return data || 'N/A';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return (row.vehicle_make || '') + ' ' + (row.vehicle_model || '') + 
                           (row.vehicle_year ? ' (' + row.vehicle_year + ')' : '');
                }
            },
            {
                "data": "service_name",
                "render": function(data, type, row) {
                    return data || 'N/A';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.date + ' ' + (row.time || '');
                }
            },
            {
                "data": "status",
                "render": function(data, type, row) {
                    const statusColors = {
                        'pending': 'warning',
                        'confirmed': 'info',
                        'in_progress': 'primary',
                        'completed': 'success',
                        'cancelled': 'danger'
                    };
                    const color = statusColors[data] || 'secondary';
                    return '<span class="badge bg-' + color + '">' + 
                           data.charAt(0).toUpperCase() + data.slice(1).replace('_', ' ') + '</span>';
                }
            },
            {
                "data": "priority",
                "render": function(data, type, row) {
                    const priorityColors = {
                        'normal': 'info',
                        'waiter': 'warning'
                    };
                    const color = priorityColors[data] || 'secondary';
                    return '<span class="badge bg-' + color + '">' + 
                           (data || 'normal').charAt(0).toUpperCase() + (data || 'normal').slice(1) + '</span>';
                }
            },
            {
                "data": "price",
                "render": function(data, type, row) {
                    return data ? '$' + parseFloat(data).toFixed(2) : 'N/A';
                }
            },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return '<a href="<?= base_url('car_wash/view/') ?>' + row.id + '" class="btn btn-sm btn-primary">' +
                           '<i class="fas fa-eye"></i></a>';
                }
            }
        ],
        "order": [[0, "desc"]],
        "pageLength": 10,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": ['excel', 'pdf', 'print'],
        "language": {
            "processing": "<?= lang('App.processing') ?>",
            "search": "<?= lang('App.search') ?>",
            "lengthMenu": "<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>",
            "info": "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.entries') ?>",
            "infoEmpty": "<?= lang('App.no_entries') ?>",
            "infoFiltered": "(<?= lang('App.filtered_from') ?> _MAX_ <?= lang('App.total_entries') ?>)",
            "emptyTable": "<?= lang('App.no_data_available') ?>",
            "zeroRecords": "<?= lang('App.no_matching_records') ?>",
            "paginate": {
                "first": "<?= lang('App.first') ?>",
                "last": "<?= lang('App.last') ?>",
                "next": "<?= lang('App.next') ?>",
                "previous": "<?= lang('App.previous') ?>"
            }
        }
    });
}

function applyDashboardFilters() {
    // Show loading
    if (typeof showToast === 'function') {
        showToast('<?= lang('App.applying_filters') ?>', 'info');
    } else {
        console.log('<?= lang('App.applying_filters') ?>');
    }
    
    // Get filter values
    const filters = {
        client_filter: $('#dashboard_client_filter').val(),
        status_filter: $('#dashboard_status_filter').val(),
        service_filter: $('#dashboard_service_filter').val(),
        date_from_filter: $('#dashboard_date_from').val(),
        date_to_filter: $('#dashboard_date_to').val()
    };
    
    // Reload dashboard content with filters
    const params = new URLSearchParams(filters).toString();
    
    // Check if loadTabContent is available, if not define a local version
    if (typeof window.loadTabContent === 'function') {
        loadTabContent('dashboard', '<?= base_url('car_wash/dashboard_content') ?>' + (params ? '?' + params : ''));
    } else {
        // Fallback: reload the page with filters
        const url = '<?= base_url('car_wash/dashboard_content') ?>' + (params ? '?' + params : '');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#dashboard-content').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard content:', error);
                if (typeof showToast === 'function') {
                    showToast('<?= lang('App.error_loading_content') ?>', 'error');
                } else {
                    console.error('<?= lang('App.error_loading_content') ?>');
                }
            }
        });
    }
    
    // Reload table
    if ($.fn.DataTable.isDataTable('#dashboardOrdersTable')) {
        $('#dashboardOrdersTable').DataTable().ajax.reload();
    }
}

function clearDashboardFilters() {
    $('#dashboardFiltersForm')[0].reset();
    applyDashboardFilters();
}

// Global function to refresh dashboard
window.refreshDashboard = function() {
    // Reload dashboard content
    if (typeof window.loadTabContent === 'function') {
        loadTabContent('dashboard', '<?= base_url('car_wash/dashboard_content') ?>');
    } else {
        // Fallback: reload the page
        $.ajax({
            url: '<?= base_url('car_wash/dashboard_content') ?>',
            type: 'GET',
            success: function(response) {
                $('#dashboard-content').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard content:', error);
                if (typeof showToast === 'function') {
                    showToast('<?= lang('App.error_loading_content') ?>', 'error');
                } else {
                    console.error('<?= lang('App.error_loading_content') ?>');
        }
            }
        });
    }
    
    // Reload table if exists
    if ($.fn.DataTable.isDataTable('#dashboardOrdersTable')) {
        $('#dashboardOrdersTable').DataTable().ajax.reload();
    }
};
</script> 

<style>
.text-purple {
    color: #6f42c1 !important;
}

.counter-value {
    font-size: 1.5rem;
    font-weight: bold;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
}

/* Removed .avatar-sm styles as they're no longer used */

.table-light th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
    border-radius: 8px;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
    font-weight: 600;
    min-width: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.bg-opacity-25 {
    --bs-bg-opacity: 0.25;
}

/* Service and client icons styling */
.fas.fs-4 {
    font-size: 1.5rem !important;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.list-group-item:hover .fas.fs-4 {
    background-color: rgba(0, 0, 0, 0.1);
    transform: scale(1.1);
}

/* Card header styling */
.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-color: #dee2e6;
}

.card-header.bg-light .card-title {
    color: #495057 !important;
    font-size: 1.1rem;
    font-weight: 600;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.form-select-sm, .form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-xl-2 {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .fas.fs-4 {
        width: 32px;
        height: 32px;
        font-size: 1.25rem !important;
    }
    
    .counter-value {
        font-size: 1.25rem;
    }
    
    .badge.fs-6 {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem;
        min-width: 30px;
    }
}
</style> 