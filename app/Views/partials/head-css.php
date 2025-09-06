<!-- Base URL and Global Variables -->
<script>
    window.baseUrl = '<?= base_url() ?>';
    window.assetsUrl = '<?= base_url('assets') ?>';
    window.csrfTokenName = '<?= csrf_token() ?>';
    window.csrfHash = '<?= csrf_hash() ?>';
    
    // Helper function to build asset URLs
    window.buildAssetUrl = function(path) {
        return window.baseUrl + (path.startsWith('/') ? path.substring(1) : path);
    };
</script>

<!-- Preload style to prevent FOUC -->
<style>
    body {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    body.loaded {
        visibility: visible;
        opacity: 1;
    }
</style>

<!-- Asset Path Fix Script -->
<script src="<?= base_url('assets/js/asset-path-fix.js') ?>"></script>

<!-- Layout config Js -->
<script src="<?= base_url('assets/js/layout.js') ?>"></script>
<!-- Bootstrap Css -->
<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?= base_url('assets/css/app.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- Flatpickr CSS -->
<link href="<?= base_url('assets/libs/flatpickr/flatpickr.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- DataTables CSS - Now loaded via datatables-styles.php to avoid duplication -->

<!-- Choices.js CSS -->
<link href="<?= base_url('assets/libs/choices.js/public/assets/styles/choices.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 CSS -->
<link href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Toastify CSS -->
<link href="<?= base_url('assets/libs/toastify/toastify.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Sales Orders CSS -->
<link href="<?= base_url('assets/css/sales-orders.css') ?>" rel="stylesheet" type="text/css" />


<!-- custom Css-->
<link href="<?= base_url('assets/css/custom.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- MDA Unified Theme CSS -->
<link href="<?= base_url('assets/css/mda-theme.css') ?>" rel="stylesheet" type="text/css" />

<!-- Modal Fixes - Clean and Minimal -->
<style>
/* ================================================ */
/* BOOTSTRAP MODAL FIXES - CLEAN IMPLEMENTATION */
/* ================================================ */

/* Basic z-index layering - let Bootstrap handle the rest */
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

/* Global modal dialog sizing */
#global-sales-order-modal .modal-dialog {
    max-width: 1200px;
    width: 90vw;
    margin: 1.5rem auto;
}

/* Enhanced SMS modal keeps its custom width */
#enhancedSmsModal .modal-dialog {
    max-width: 80vw;
}

/* Mobile responsiveness */
@media (max-width: 991px) {
    #global-sales-order-modal .modal-dialog {
        width: 95vw;
        margin: 1rem auto;
    }
}

@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    #global-sales-order-modal .modal-dialog {
        width: 100vw;
        margin: 0;
        height: 100vh;
        max-width: 100%;
    }
    
    #global-sales-order-modal .modal-content {
        height: 100vh;
        border-radius: 0;
    }
}
</style>

<!-- Custom fixes CSS -->
<link href="<?= base_url('assets/css/custom-fixes.css') ?>" rel="stylesheet" type="text/css" />

<!-- Keyboard Shortcuts CSS -->
<link href="<?= base_url('assets/css/keyboard-shortcuts.css') ?>" rel="stylesheet" type="text/css" />