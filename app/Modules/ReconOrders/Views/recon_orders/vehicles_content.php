<div class="container-fluid">
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-blue">
                                <i class="ri-car-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="totalVehicles">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.total_vehicles') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-green">
                                <i class="ri-calendar-check-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="recentVehicles">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.new_this_month') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-orange">
                                <i class="ri-trophy-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="mostServicedCount">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.most_serviced') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-purple">
                                <i class="ri-building-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="popularMakesCount">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.vehicle_makes') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-car-line me-2"></i>
                                <?= lang('App.vehicle_registry') ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= lang('App.complete_history_vehicles') ?></p>
                        </div>
                        <div class="col-auto">
                            <div class="search-bar">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" class="form-control" id="vehicleSearch" placeholder="<?= lang('App.search') ?> VIN, <?= lang('App.vehicle_make') ?>, <?= lang('App.vehicle_model') ?>...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="vehiclesTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.vin_number') ?></th>
                                    <th><?= lang('App.vehicle_details') ?></th>
                                    <th><?= lang('App.vehicle_year') ?></th>
                                    <th><?= lang('App.total_services') ?></th>
                                    <th><?= lang('App.first_service') ?></th>
                                    <th><?= lang('App.last_service') ?></th>
                                    <th><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background: #ffffff;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.vehicle-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.vehicle-info {
    font-weight: 600;
    color: #2563eb;
}

.vin-number {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #64748b;
}

.service-count {
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.vehicle-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.search-bar {
    max-width: 400px;
}

.icon-container {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.icon-blue {
    background: #3b82f6;
}

.icon-green {
    background: #10b981;
}

.icon-orange {
    background: #f59e0b;
}

.icon-purple {
    background: #8b5cf6;
}
</style>

<script>
// Wait for the document to be ready and ensure jQuery is available
document.addEventListener('DOMContentLoaded', function() {
    // Function to initialize vehicles table
    function initVehiclesTable() {
        // Check if jQuery and DataTables are available
        if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
            console.log('⏳ Waiting for jQuery to load...');
            setTimeout(initVehiclesTable, 100);
            return;
        }
        
        if (typeof window.$.fn.DataTable === 'undefined') {
            console.log('⏳ Waiting for DataTables to load...');
            setTimeout(initVehiclesTable, 100);
            return;
        }
        
        console.log('🚗 Vehicles Registry - Initializing');
        
        // Use jQuery safely
        const $ = window.jQuery;

    // Initialize DataTable
    const vehiclesTable = $('#vehiclesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('vehicles/data') ?>',
            type: 'POST',
            data: function(d) {
                d.ajax = true;
                console.log('Sending AJAX request with data:', d);
                return d;
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', error, thrown);
                console.error('Response:', xhr.responseText);
                showToast('Error loading vehicles data', 'error');
            },
            success: function(data) {
                console.log('DataTable Ajax Success:', data);
            }
        },
        columns: [
            {
                data: 'vehicle_info',
                render: function(data, type, row) {
                    return `<div class="vehicle-info">${data}</div>`;
                }
            },
            {
                data: 'vin_number',
                render: function(data, type, row) {
                    return `<span class="vin-number">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    // Since we don't have make/model in the database, show vehicle info
                    return `<span class="text-muted">Not available</span>`;
                }
            },
            {
                data: 'year',
                render: function(data, type, row) {
                    return '<span class="text-muted">Not available</span>';
                }
            },
            {
                data: 'total_orders',
                render: function(data, type, row) {
                    return `<div class="text-center">
                        <span class="service-count">${data || 0}</span>
                    </div>`;
                }
            },
            {
                data: 'first_service',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<div>
                            <div class="fw-medium">${data}</div>
                            <small class="text-muted">${row.first_order_number || ''}</small>
                        </div>`;
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: 'last_service',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<div>
                            <div class="fw-medium">${data}</div>
                            <small class="text-muted">${row.last_order_number || ''}</small>
                        </div>`;
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `<div class="vehicle-actions">
                        <a href="<?= base_url('vehicles/') ?>${row.vin_number.slice(-6)}" 
                           class="btn btn-outline-primary btn-sm" 
                           title="<?= lang('App.view_vehicle_history') ?>">
                            <i class="ri-eye-line"></i> <?= lang('App.view_vehicle_history') ?>
                        </a>
                    </div>`;
                }
            }
        ],
        order: [[5, 'desc']], // Sort by last service date
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
            emptyTable: '<div class="text-center py-4"><i class="ri-car-line display-4 text-muted"></i><h6 class="mt-2">No vehicles found</h6><p class="text-muted">Vehicles will appear here when orders are created</p></div>'
        },
        drawCallback: function(settings) {
            // Re-initialize tooltips with Bootstrap 5
            if (typeof bootstrap !== 'undefined') {
                const tooltips = document.querySelectorAll('[title]');
                tooltips.forEach(el => new bootstrap.Tooltip(el));
            }
        }
    });

    // Search functionality
    $('#vehicleSearch').on('keyup', function() {
        vehiclesTable.search(this.value).draw();
    });

    function refreshStats() {
        $.get('<?= base_url('vehicles/stats') ?>', function(data) {
            if (data.stats) {
                $('#totalVehicles').text(data.stats.total_vehicles || 0);
                $('#recentVehicles').text(data.stats.recent_vehicles || 0);
                $('#mostServicedCount').text(data.stats.most_serviced ? data.stats.most_serviced.total_orders : 0);
                $('#popularMakesCount').text(data.stats.popular_makes ? data.stats.popular_makes.length : 0);
            }
        }).fail(function() {
            console.warn('Failed to refresh vehicle stats');
        });
    }

    // Refresh stats every 30 seconds
    setInterval(refreshStats, 30000);

    // Initial stats load
    refreshStats();

    console.log('✅ Vehicles Registry initialized successfully');
    }

    // Initialize the vehicles table
    initVehiclesTable();
});

function showToast(message, type = 'success') {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: type === 'success' ? "#10b981" : "#ef4444",
    }).showToast();
}
</script>