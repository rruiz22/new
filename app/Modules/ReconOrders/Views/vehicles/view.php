<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Vehicle Details' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Vehicle Details' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>">Recon Orders</a></li>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders/vehicles') ?>">Vehicles</a></li>
<li class="breadcrumb-item active"><?= $vehicle['vehicle_info'] ?? 'Vehicle Details' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.vehicle-header {
    background: #3b82f6;
    color: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.vehicle-info-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #64748b;
}

.info-value {
    font-weight: 500;
    color: #1e293b;
}

.vin-display {
    font-family: 'Courier New', monospace;
    background: #f8fafc;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 1.1rem;
    letter-spacing: 1px;
}

.service-timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 3rem;
    padding-bottom: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    width: 2px;
    height: 100%;
    background: #e2e8f0;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: #3b82f6;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #3b82f6;
}

.timeline-content {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.order-status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.service-date {
    color: #059669;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .vehicle-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .timeline-item {
        padding-left: 2rem;
    }
    
    .timeline-marker {
        left: 0.25rem;
    }
    
    .timeline-item::before {
        left: 0.75rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (isset($vehicle) && !empty($vehicle)): ?>
<div class="container-fluid">
    
    <!-- Vehicle Header -->
    <div class="vehicle-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2"><?= esc($vehicle['vehicle_info']) ?></h1>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="vin-display">
                        <i class="ri-barcode-line me-2"></i>
                        VIN: <?= esc($vehicle['vin_number']) ?>
                    </div>
                    <?php if ($vehicle['year']): ?>
                    <span class="badge bg-light text-dark px-3 py-2" style="font-size: 0.9rem;">
                        <?= $vehicle['year'] ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?= base_url('recon_orders/vehicles') ?>" class="btn btn-light">
                    <i class="ri-arrow-left-line me-2"></i>
                    Back to Vehicles
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Vehicle Information -->
        <div class="col-xl-4 col-lg-5">
            <div class="card vehicle-info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>
                        Vehicle Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">Make</span>
                        <span class="info-value"><?= $vehicle['make'] ?: 'Not specified' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Model</span>
                        <span class="info-value"><?= $vehicle['model'] ?: 'Not specified' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Year</span>
                        <span class="info-value"><?= $vehicle['year'] ?: 'Unknown' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Color</span>
                        <span class="info-value"><?= $vehicle['color'] ?: 'Not specified' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">First Service</span>
                        <span class="info-value"><?= date('M d, Y', strtotime($vehicle['created_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value"><?= date('M d, Y', strtotime($vehicle['updated_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value text-primary"><?= $vehicle['total_orders'] ?></div>
                    <div class="stat-label">Total Services</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-success"><?= count($vehicle['orders']) ?></div>
                    <div class="stat-label">Orders Found</div>
                </div>
            </div>
        </div>

        <!-- Service History Timeline -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-history-line me-2"></i>
                        Service History
                        <span class="badge bg-primary ms-2"><?= count($vehicle['orders']) ?> orders</span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($vehicle['orders'])): ?>
                    <div class="service-timeline">
                        <?php foreach ($vehicle['orders'] as $order): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="<?= base_url('recon_orders/view/' . $order['id']) ?>" 
                                               class="text-decoration-none">
                                                Order #<?= $order['order_number'] ?>
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'in_progress' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?> order-status-badge">
                                            <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Client</small>
                                        <strong><?= $order['client_name'] ?: 'Not assigned' ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Service</small>
                                        <strong><?= $order['service_name'] ?: 'Not specified' ?></strong>
                                    </div>
                                    <?php if ($order['service_date']): ?>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Service Date</small>
                                        <span class="service-date">
                                            📅 <?= date('M d, Y', strtotime($order['service_date'])) ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Stock Number</small>
                                        <strong><?= $order['stock'] ?: 'N/A' ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Pictures</small>
                                        <span class="badge bg-<?= $order['pictures'] ? 'success' : 'secondary' ?>">
                                            <?= $order['pictures'] ? 'Taken' : 'Pending' ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($order['notes'])): ?>
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-1">Notes</small>
                                    <div class="bg-light p-2 rounded">
                                        <small><?= nl2br(esc($order['notes'])) ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ri-file-list-line display-4 text-muted"></i>
                        <h6 class="mt-3">No service history found</h6>
                        <p class="text-muted">This vehicle doesn't have any service orders yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-error-warning-line display-4 text-warning"></i>
                    <h4 class="mt-3">Vehicle Not Found</h4>
                    <p class="text-muted">The requested vehicle could not be found or you don't have permission to view it.</p>
                    <a href="<?= base_url('recon_orders/vehicles') ?>" class="btn btn-primary">
                        <i class="ri-arrow-left-line me-2"></i>
                        Back to Vehicles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚗 Vehicle Details - Page loaded');
    
    // Add smooth scrolling for any internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    console.log('✅ Vehicle Details - Initialized successfully');
});
</script>
<?= $this->endSection() ?> 