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
<!-- DataTables CSS -->
<link href="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/libs/datatables/responsive.bootstrap5.min.css') ?>" rel="stylesheet" type="text/css" />

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

<!-- Custom fixes CSS -->
<link href="<?= base_url('assets/css/custom-fixes.css') ?>" rel="stylesheet" type="text/css" />