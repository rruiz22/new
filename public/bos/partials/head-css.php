<?php
// Get the base URL dynamically
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$appPath = str_replace('/public/bos', '', dirname($_SERVER['SCRIPT_NAME']));
$fullBaseUrl = $baseUrl . $appPath;
?>

<!-- Base URL and Global Variables -->
<script>
    window.baseUrl = '<?= $fullBaseUrl ?>/';
    window.assetsUrl = '<?= $fullBaseUrl ?>/assets';
    
    // Helper function to build asset URLs
    window.buildAssetUrl = function(path) {
        return window.baseUrl + (path.startsWith('/') ? path.substring(1) : path);
    };
</script>

<!-- App favicon -->
<link rel="shortcut icon" href="<?= $fullBaseUrl ?>/assets/images/favicon.ico">

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

<!-- Bootstrap Css -->
<link href="<?= $fullBaseUrl ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?= $fullBaseUrl ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?= $fullBaseUrl ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />

<!-- DataTables CSS -->
<link href="<?= $fullBaseUrl ?>/assets/libs/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $fullBaseUrl ?>/assets/libs/datatables/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 CSS -->
<link href="<?= $fullBaseUrl ?>/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

<!-- Node Waves CSS -->
<link href="<?= $fullBaseUrl ?>/assets/libs/node-waves/waves.min.css" rel="stylesheet" type="text/css" />
