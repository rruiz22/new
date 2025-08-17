<!-- DataTables JS -->
<script src="<?= base_url('assets/libs/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/responsive.bootstrap5.min.js') ?>"></script>

<!-- DataTables Buttons Extension -->
<script src="<?= base_url('assets/libs/datatables/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/buttons.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/buttons.colVis.min.js') ?>"></script>

<script>
// Wait for jQuery to be available before initializing DataTables
$(document).ready(function() {
    // Verify jQuery and DataTables are available
    if (typeof $ === 'undefined' || typeof jQuery === 'undefined') {
        console.error('❌ jQuery not available for DataTables initialization');
        return;
    }
    
    if (typeof $.fn.dataTable === 'undefined') {
        console.error('❌ DataTables library not loaded');
        return;
    }

    console.log('✅ DataTables initialization starting...');

    // DataTables default configuration
    $.extend(true, $.fn.dataTable.defaults, {
        responsive: false,
        scrollX: false,
        autoWidth: false,
        processing: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            processing: `
                <div class="d-flex justify-content-center align-items-center p-4">
                    <div class="spinner-border text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Loading...</span>
                </div>
            `,
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No matching records found",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Helper function to get language-specific DataTables configuration
    window.getDataTablesLanguage = function() {
        const lang = document.documentElement.lang || 'en';
        const langMap = {
            'en': 'en-US',
            'es': 'es-ES',
            'pt': 'pt-BR'
        };
        
        return {
            url: "<?= base_url('assets/libs/datatables/i18n/') ?>" + langMap[lang] + ".json"
        };
    };

    // Helper function to initialize tooltips after DataTable draw
    window.initializeDataTableTooltips = function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    };

    // Helper function to initialize Feather icons after DataTable draw
    window.initializeDataTableFeatherIcons = function() {
        if (typeof feather !== 'undefined') {
            setTimeout(() => {
                feather.replace();
            }, 50);
        }
    };

    // Common DataTable initialization callback
    window.commonDataTableInitComplete = function(settings, json) {
        const table = this.api();
        
        // Force multiple adjustments after initialization
        setTimeout(() => {
            table.columns.adjust();
            $(table.table().node()).css('width', '100%');
        }, 50);
        
        setTimeout(() => {
            table.columns.adjust().draw();
        }, 150);
        
        setTimeout(() => {
            table.columns.adjust();
            $('.dataTables_wrapper').css('width', '100%');
        }, 300);
    };

    // Common DataTable draw callback
    window.commonDataTableDrawCallback = function(settings) {
        // Initialize tooltips
        window.initializeDataTableTooltips();
        
        // Initialize Feather icons
        window.initializeDataTableFeatherIcons();
        
        // Ensure table uses full width on every draw
        $(this.api().table().node()).css('width', '100%');
        $('.dataTables_wrapper').css('width', '100%');
    };

    console.log('✅ DataTables configuration and helpers loaded successfully');

}); // End document.ready
</script>