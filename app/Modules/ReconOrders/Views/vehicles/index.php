<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Vehicles Registry' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Vehicles Registry' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>">Recon Orders</a></li>
<li class="breadcrumb-item active"><?= $title ?? 'Vehicles Registry' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #3b82f6; border-radius: 12px;">
                                <i class="ri-car-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="totalVehicles"><?= $stats['total_vehicles'] ?? 0 ?></h5>
                            <p class="text-muted mb-0">Total Vehicles</p>
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
                            <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #10b981; border-radius: 12px;">
                                <i class="ri-calendar-check-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="recentVehicles"><?= $stats['recent_vehicles'] ?? 0 ?></h5>
                            <p class="text-muted mb-0">New This Month</p>
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
                            <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #f59e0b; border-radius: 12px;">
                                <i class="ri-trophy-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="mostServicedCount">
                                <?= isset($stats['most_serviced']) ? $stats['most_serviced']['total_orders'] : 0 ?>
                            </h5>
                            <p class="text-muted mb-0">Most Serviced</p>
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
                            <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #8b5cf6; border-radius: 12px;">
                                <i class="ri-building-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="popularMakesCount">
                                <?= isset($stats['popular_makes']) ? count($stats['popular_makes']) : 0 ?>
                            </h5>
                            <p class="text-muted mb-0">Vehicle Makes</p>
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
                                Vehicles Registry
                            </h5>
                            <p class="text-muted small mb-0">Complete history of vehicles serviced</p>
                        </div>
                        <div class="col-auto">
                            <div class="search-bar">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" class="form-control" id="vehicleSearch" placeholder="Search VIN, make, model...">
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
                                    <th>Vehicle</th>
                                    <th>VIN Number</th>
                                    <th>Make/Model</th>
                                    <th>Year</th>
                                    <th>Services</th>
                                    <th>First Service</th>
                                    <th>Last Service</th>
                                    <th>Actions</th>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    console.log('🚗 Vehicles Registry - Initializing');

    // Initialize DataTable
    const vehiclesTable = $('#vehiclesTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('recon_orders/vehicles/data') ?>',
            type: 'POST',
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', error, thrown);
                showToast('error', 'Error loading vehicles data');
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
                    const make = row.make !== 'N/A' ? row.make : '';
                    const model = row.model !== 'N/A' ? row.model : '';
                    if (make && model) {
                        return `${make} ${model}`;
                    } else if (make) {
                        return make;
                    } else if (model) {
                        return model;
                    }
                    return '<span class="text-muted">Not parsed</span>';
                }
            },
            {
                data: 'year',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<span class="vehicle-badge badge bg-info">${data}</span>`;
                    }
                    return '<span class="text-muted">Unknown</span>';
                }
            },
            {
                data: 'total_orders',
                render: function(data, type, row) {
                    return `<div class="text-center">
                        <span class="service-count">${data}</span>
                    </div>`;
                }
            },
            {
                data: 'first_service',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<div>
                            <div class="fw-medium">${data}</div>
                            <small class="text-muted">${row.first_order_number}</small>
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
                            <small class="text-muted">${row.last_order_number}</small>
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
                        <a href="<?= base_url('recon_orders/vehicles/view/') ?>${row.id}" 
                           class="btn btn-outline-primary btn-sm" 
                           title="View Vehicle History">
                            <i class="ri-eye-line"></i> View History
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
            // Re-initialize tooltips
            $('[title]').tooltip();
        }
    });

    // Search functionality
    $('#vehicleSearch').on('keyup', function() {
        vehiclesTable.search(this.value).draw();
    });

    // Refresh stats every 30 seconds
    setInterval(refreshStats, 30000);

    console.log('✅ Vehicles Registry initialized successfully');
});

function refreshStats() {
    $.get('<?= base_url('recon_orders/vehicles/stats') ?>', function(data) {
        if (data.success) {
            $('#totalVehicles').text(data.stats.total_vehicles || 0);
            $('#recentVehicles').text(data.stats.recent_vehicles || 0);
            $('#mostServicedCount').text(data.stats.most_serviced ? data.stats.most_serviced.total_orders : 0);
            $('#popularMakesCount').text(data.stats.popular_makes ? data.stats.popular_makes.length : 0);
        }
    }).fail(function() {
        console.warn('Failed to refresh vehicle stats');
    });
}

function showToast(type, message) {
    // Assuming you have a toast function available
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'success' ? "#10b981" : "#ef4444",
        }).showToast();
    } else {
        alert(message);
    }
}
</script>
<?= $this->endSection() ?> 