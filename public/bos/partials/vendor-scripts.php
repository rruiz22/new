<?php
// Get the base URL dynamically
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$appPath = str_replace('/public/bos', '', dirname($_SERVER['SCRIPT_NAME']));
$fullBaseUrl = $baseUrl . $appPath;
?>

<!-- JAVASCRIPT -->
<script src="<?= $fullBaseUrl ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= $fullBaseUrl ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $fullBaseUrl ?>/assets/libs/feather-icons/feather.min.js"></script>

<!-- Node Waves -->
<script src="<?= $fullBaseUrl ?>/assets/libs/node-waves/waves.min.js"></script>

<!-- DataTables -->
<script src="<?= $fullBaseUrl ?>/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $fullBaseUrl ?>/assets/libs/datatables/dataTables.bootstrap5.min.js"></script>
<script src="<?= $fullBaseUrl ?>/assets/libs/datatables/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?= $fullBaseUrl ?>/assets/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- App JS -->
<script src="<?= $fullBaseUrl ?>/assets/js/app.js"></script>

<!-- Initialize body loaded class -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('loaded');
    
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // SweetAlert2 is already available as Swal globally
    if (typeof Swal === 'undefined' && typeof Sweetalert2 !== 'undefined') {
        window.Swal = Sweetalert2;
    }
    
    // Debug SweetAlert2 availability
    console.log('SweetAlert2 available:', typeof Swal !== 'undefined');
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('Feather available:', typeof feather !== 'undefined');
});
</script>
