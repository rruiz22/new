<!-- JAVASCRIPT from CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>

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
