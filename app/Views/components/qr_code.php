<?php
/**
 * QR Code Component
 * Reutilizable para todos los módulos (Sales Orders, Service Orders, CarWash, Recon)
 * 
 * Parámetros requeridos:
 * - $order: Array con datos de la orden
 * - $qr_data: Array con datos del QR (opcional, si no está disponible)
 * - $module_prefix: Prefijo del módulo ('SAL', 'SER', 'CAR', 'REC')
 * 
 * Parámetros opcionales:
 * - $show_sidebar: Mostrar card sidebar (default: true)
 * - $show_topbar: Mostrar en topbar (default: false)
 * - $sidebar_qr_size: Tamaño QR en sidebar (default: 200px)
 */

$order = $order ?? [];
$qr_data = $qr_data ?? null;
$module_prefix = $module_prefix ?? 'SAL';
$show_sidebar = $show_sidebar ?? true;
$show_topbar = $show_topbar ?? false;
$sidebar_qr_size = $sidebar_qr_size ?? 200;

// Generar IDs únicos para el componente
$component_id = 'qr_code_' . strtolower($module_prefix) . '_' . ($order['id'] ?? 0);
$modal_id = $component_id . '_modal';
$order_number = $module_prefix . '-' . str_pad($order['id'] ?? 0, 5, '0', STR_PAD_LEFT);
?>

<!-- QR Code Component Styles -->
<style>
/* QR Code Modal Styles */
.qr-container {
    transition: all 0.3s ease;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-container img {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.qr-container img:hover {
    transform: scale(1.02);
}

/* Topbar QR Styles */
.qr-topbar-image {
    transition: transform 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.qr-topbar-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.qr-topbar-container {
    padding: 8px;
}

/* QR Sidebar Card Styles */
.qr-sidebar-image {
    transition: transform 0.3s ease;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.qr-sidebar-image:hover {
    transform: scale(1.02) !important;
    box-shadow: 0 6px 25px rgba(0,0,0,0.2);
}

.qr-large-display {
    padding: 10px;
    background: linear-gradient(145deg, #f8f9fa, #ffffff);
    border-radius: 15px;
    border: 1px solid rgba(0,0,0,0.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .qr-container {
        min-height: 250px;
    }
    
    .qr-container img {
        max-width: 250px !important;
    }
    
    .qr-topbar-image {
        width: 50px !important;
        height: 50px !important;
    }
}

@media (max-width: 1200px) {
    .qr-sidebar-image {
        max-width: 150px !important;
    }
}
</style>

<?php if ($show_sidebar): ?>
<!-- QR Code Sidebar Card -->
<?php if (isset($qr_data) && $qr_data): ?>
<div class="card mb-4 d-none d-md-block qr-code-sidebar" id="<?= $component_id ?>_sidebar">
    <div class="card-header">
        <h5 class="card-title mb-0 fw-bold">
            <i data-feather="smartphone" class="icon-sm me-2"></i>
            QR Code Access
        </h5>
        <small class="text-muted"><?= lang('App.instant_mobile_access') ?? 'Instant Mobile Access' ?></small>
    </div>
    <div class="card-body text-center">
        <!-- Large QR Code Display -->
        <div class="qr-large-display">
            <img src="<?= $qr_data['qr_url'] ?>" 
                 alt="QR Code for Order <?= $order_number ?>" 
                 class="qr-sidebar-image" 
                 style="width: <?= $sidebar_qr_size ?>px; height: <?= $sidebar_qr_size ?>px; border-radius: 12px; cursor: pointer;"
                 onclick="showQRModal('<?= $modal_id ?>')"
                 title="Click to view larger">
        </div>
        
        <!-- Short URL Display -->
        <div class="mt-3">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control text-center" 
                       id="<?= $component_id ?>_short_url"
                       value="<?= $qr_data['short_url'] ?>" 
                       readonly 
                       style="font-size: 0.75rem;">
                <button class="btn btn-outline-secondary btn-sm" 
                        type="button" 
                        onclick="copyShortUrl('<?= $component_id ?>_short_url')"
                        title="Copy URL">
                    <i class="ri-file-copy-line"></i>
                </button>
            </div>
            <small class="text-muted d-block mt-1">
                <?= $qr_data['shortener'] ?? 'Direct URL' ?>
            </small>
        </div>
    </div>
</div>
<?php else: ?>
<!-- QR Code Not Available Card -->
<div class="card mb-4 d-none d-md-block qr-code-sidebar" id="<?= $component_id ?>_sidebar">
    <div class="card-header">
        <h5 class="card-title mb-0 fw-bold">
            <i data-feather="smartphone" class="icon-sm me-2"></i>
            QR Code Access
        </h5>
    </div>
    <div class="card-body text-center">
        <div class="py-4">
            <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
            <h6 class="text-warning mb-2">QR Code Unavailable</h6>
            <p class="text-muted small">Lima Links API not configured</p>
            <button class="btn btn-outline-primary btn-sm" onclick="generateQRCode(<?= $order['id'] ?? 0 ?>, '<?= strtolower($module_prefix) ?>')">
                <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                Generate QR Code
            </button>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($show_topbar): ?>
<!-- QR Code Topbar Display -->
<?php if (isset($qr_data) && $qr_data): ?>
<div class="qr-topbar-container">
    <img src="<?= $qr_data['qr_url'] ?>" 
         alt="QR Code for Order <?= $order_number ?>" 
         class="qr-topbar-image" 
         style="width: 60px; height: 60px; cursor: pointer;"
         onclick="showQRModal('<?= $modal_id ?>')"
         title="QR Code - Click to enlarge">
</div>
<?php else: ?>
<div class="qr-topbar-container">
    <button class="btn btn-outline-secondary btn-sm" onclick="generateQRCode(<?= $order['id'] ?? 0 ?>, '<?= strtolower($module_prefix) ?>')">
        <i data-feather="smartphone" class="icon-xs"></i>
    </button>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- QR Code Modal -->
<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="<?= $modal_id ?>_label">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code - Order <?= $order_number ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data): ?>
                <!-- QR Code Display -->
                <div class="qr-large-container mb-4">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Order <?= $order_number ?>" 
                         class="img-fluid" 
                         style="max-width: 300px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                </div>
                
                <!-- Short URL Display -->
                <div class="mb-4">
                    <label class="form-label text-muted small">Short URL</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-center" 
                               id="<?= $modal_id ?>_url_input" 
                               value="<?= $qr_data['short_url'] ?>" 
                               readonly>
                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('<?= $modal_id ?>_url_input')">
                            <i data-feather="copy" class="icon-xs"></i>
                        </button>
                    </div>
                    <small class="text-muted">
                        Powered by <?= $qr_data['shortener'] ?? 'Direct URL' ?>
                    </small>
                </div>
                
                <!-- QR Code Actions -->
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="downloadQRCode('<?= $qr_data['qr_url'] ?>', '<?= $order_number ?>')">
                        <i data-feather="download" class="icon-xs me-1"></i>
                        Download
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printQRCode('<?= $qr_data['qr_url'] ?>', '<?= $order_number ?>')">
                        <i data-feather="printer" class="icon-xs me-1"></i>
                        Print
                    </button>
                </div>
                <?php else: ?>
                <!-- QR Code Not Available -->
                <div class="text-center py-4">
                    <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
                    <h6 class="text-warning">QR Code Unavailable</h6>
                    <p class="text-muted small">Lima Links API not configured</p>
                    <button class="btn btn-primary btn-sm" onclick="generateQRCode(<?= $order['id'] ?? 0 ?>, '<?= strtolower($module_prefix) ?>')">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        Generate QR Code
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Component JavaScript -->
<script>
// Function to show QR Modal
function showQRModal(modalId) {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

// Function to copy short URL
function copyShortUrl(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    document.execCommand('copy');
    
    // Show toast notification
    showToast('Short URL copied to clipboard!', 'success');
}

// Function to copy to clipboard (generic)
function copyToClipboard(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    document.execCommand('copy');
    
    // Show toast notification
    showToast('Copied to clipboard!', 'success');
}

// Function to generate QR Code
function generateQRCode(orderId, moduleType) {
    // Show loading state
    showToast('Generating QR Code...', 'info');
    
    // Make AJAX request to generate QR
    fetch(`/api/generate-qr/${moduleType}/${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('QR Code generated successfully!', 'success');
            // Reload the page to show the new QR code
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to generate QR Code', 'error');
        }
    })
    .catch(error => {
        console.error('Error generating QR:', error);
        showToast('Error generating QR Code', 'error');
    });
}

// Function to download QR Code
function downloadQRCode(qrUrl, orderNumber) {
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = `QR-${orderNumber}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Function to print QR Code
function printQRCode(qrUrl, orderNumber) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>QR Code - ${orderNumber}</title>
            <style>
                body { margin: 0; padding: 20px; text-align: center; }
                img { max-width: 300px; }
                h2 { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h2>Order ${orderNumber}</h2>
            <img src="${qrUrl}" alt="QR Code for ${orderNumber}">
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Function to show toast (placeholder - debe implementarse globalmente)
function showToast(message, type = 'info') {
    console.log(`Toast [${type}]: ${message}`);
    // Implementar sistema de toast notification
}
</script>