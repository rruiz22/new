<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Recon Order PDF' ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* PDF/Print optimized styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .container-fluid {
            max-width: none !important;
            padding: 0 !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }
        
        .page-break {
            page-break-before: always;
        }
    }
    
    /* Clean print layout */
    .pdf-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .pdf-order-info {
        margin-bottom: 2rem;
    }
    
    .pdf-section {
        margin-bottom: 1.5rem;
        page-break-inside: avoid;
    }
    
    .pdf-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.75rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        padding: 0.25rem 0;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .info-value {
        color: #6c757d;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }
    
    .bg-success { background-color: #d4edda !important; color: #155724 !important; }
    .bg-warning { background-color: #fff3cd !important; color: #856404 !important; }
    .bg-info { background-color: #d1ecf1 !important; color: #0c5460 !important; }
    .bg-secondary { background-color: #e2e3e5 !important; color: #383d41 !important; }
    .bg-danger { background-color: #f8d7da !important; color: #721c24 !important; }
    .bg-primary { background-color: #d6e9ff !important; color: #004085 !important; }
    
    .text-muted {
        color: #6c757d !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (isset($order) && !empty($order)): ?>
<div class="container-fluid">
    <!-- PDF Header -->
    <div class="pdf-header">
        <h2 class="mb-1">Recon Order Details</h2>
        <h4 class="text-primary">#<?= $order['order_number'] ?></h4>
        <p class="text-muted mb-0">Generated on <?= date('F j, Y \a\t g:i A') ?></p>
    </div>

    <!-- Order Information -->
    <div class="pdf-order-info">
        <div class="row">
            <div class="col-md-6">
                <div class="pdf-section">
                    <h6>Basic Information</h6>
                    <div class="info-row">
                        <span class="info-label">Order Number:</span>
                        <span class="info-value"><?= $order['order_number'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Client:</span>
                        <span class="info-value"><?= $order['client_name'] ?? 'Not assigned' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Service:</span>
                        <span class="info-value"><?= $order['service_name'] ?? 'Not assigned' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Vehicle:</span>
                        <span class="info-value"><?= $order['vehicle'] ?? 'Not provided' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Stock:</span>
                        <span class="info-value"><?= $order['stock'] ?? 'Not provided' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">VIN:</span>
                        <span class="info-value"><?= $order['vin_number'] ?? 'Not provided' ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pdf-section">
                    <h6>Status & Timeline</h6>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge bg-<?= $statusColor ?>">
                                <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pictures:</span>
                        <span class="info-value">
                            <span class="status-badge bg-<?= $order['pictures'] ? 'success' : 'secondary' ?>">
                                <?= $order['pictures'] ? 'Taken' : 'Pending' ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Created:</span>
                        <span class="info-value"><?= date('M d, Y g:i A', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Updated:</span>
                        <span class="info-value"><?= $order['updated_at'] ? date('M d, Y g:i A', strtotime($order['updated_at'])) : 'Never' ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Service Date:</span>
                        <span class="info-value"><?= $order['service_date'] ? date('M d, Y', strtotime($order['service_date'])) : 'Not scheduled' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Notes -->
    <?php if (!empty($order['notes'])): ?>
    <div class="pdf-section page-break">
        <h6>Order Notes</h6>
        <div class="p-3 bg-light border rounded">
            <?= nl2br(esc($order['notes'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Additional Details -->
    <?php if (!empty($order['description'])): ?>
    <div class="pdf-section">
        <h6>Description</h6>
        <div class="p-3 bg-light border rounded">
            <?= nl2br(esc($order['description'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="text-center text-muted mt-4 pt-3" style="border-top: 1px solid #dee2e6;">
        <small>
            This document was generated automatically from the MDA Management System.<br>
            For questions or support, please contact your system administrator.
        </small>
    </div>
</div>

<!-- Auto-print script for PDF functionality -->
<script>
// Auto-print when the page loads (for PDF generation)
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure page is fully rendered
    setTimeout(function() {
        window.print();
    }, 500);
});

// Optional: Close window after printing (if opened in new tab)
window.addEventListener('afterprint', function() {
    // Only close if this was opened in a new window/tab
    if (window.opener) {
        window.close();
    }
});
</script>

<?php else: ?>
<div class="container-fluid">
    <div class="text-center py-5">
        <h3 class="text-danger">Order Not Found</h3>
        <p class="text-muted">The requested recon order could not be found or you don't have permission to view it.</p>
        <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
