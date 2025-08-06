<!-- DataTables CSS -->
<link href="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/libs/datatables/responsive.bootstrap5.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/libs/datatables/buttons.bootstrap5.min.css') ?>" rel="stylesheet" type="text/css" />

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

<!-- DataTables Common Styles -->
<style>
/* Force DataTable to use full width on initialization */
.dataTables_wrapper table {
    width: 100% !important;
}

.dataTables_wrapper {
    width: 100% !important;
}

/* Fix DataTable controls hover effects */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    transition: all 0.15s ease-in-out !important;
    background-color: #fff !important;
}

.dataTables_wrapper .dataTables_length select:hover,
.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.1) !important;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
    outline: 0 !important;
}

/* Style DataTable info and pagination */
.dataTables_wrapper .dataTables_info {
    padding-top: 0.75rem !important;
    color: #64748b !important;
    font-size: 0.875rem !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem !important;
    margin: 0 2px !important;
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    color: #64748b !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #adb5bd !important;
    cursor: not-allowed !important;
    opacity: 0.5 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    border-color: #e3ebf0 !important;
    background-color: transparent !important;
    color: #adb5bd !important;
}

/* DataTables Processing */
.dataTables_processing {
    background-color: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Responsive table adjustments */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Status indicators common styles */
.status-indicator {
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-completed,
.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-cancelled,
.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-processing,
.status-in_progress {
    background-color: #dbeafe;
    color: #1e40af;
}

/* Action links common styles */
.link-primary {
    color: #405189 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-primary:hover {
    color: #2c3e50 !important;
}

.link-success {
    color: #0ab39c !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-success:hover {
    color: #087f69 !important;
}

.link-danger {
    color: #f06548 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-danger:hover {
    color: #d63384 !important;
}

.link-warning {
    color: #f7b84b !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-warning:hover {
    color: #e19f28 !important;
}

.link-info {
    color: #299cdb !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-info:hover {
    color: #1c7db0 !important;
}

/* Common utility classes */
.fs-15 {
    font-size: 15px !important;
}

.hstack {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.flex-wrap {
    flex-wrap: wrap !important;
}

/* Empty state styling */
.dataTables_empty {
    padding: 3rem 0 !important;
}

/* Search box styling */
.search-box {
    position: relative;
}

.search-box .search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
}

.search-box input {
    padding-right: 35px;
}

/* DataTables buttons styling */
.dt-buttons {
    margin-bottom: 0.5rem;
}

.dt-button {
    border: 1px solid #e3ebf0 !important;
    background-color: #fff !important;
    color: #64748b !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 6px !important;
    margin-right: 0.25rem !important;
    transition: all 0.15s ease-in-out !important;
}

.dt-button:hover {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.dt-button:focus {
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
}
</style>

<script>
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
function getDataTablesLanguage() {
    const lang = document.documentElement.lang || 'en';
    const langMap = {
        'en': 'en-US',
        'es': 'es-ES',
        'pt': 'pt-BR'
    };
    
    return {
        url: "<?= base_url('assets/libs/datatables/i18n/') ?>" + langMap[lang] + ".json"
    };
}

// Helper function to initialize tooltips after DataTable draw
function initializeDataTableTooltips() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Helper function to initialize Feather icons after DataTable draw
function initializeDataTableFeatherIcons() {
    if (typeof feather !== 'undefined') {
        setTimeout(() => {
            feather.replace();
        }, 50);
    }
}

// Common DataTable initialization callback
function commonDataTableInitComplete(settings, json) {
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
}

// Common DataTable draw callback
function commonDataTableDrawCallback(settings) {
    // Initialize tooltips
    initializeDataTableTooltips();
    
    // Initialize Feather icons
    initializeDataTableFeatherIcons();
    
    // Ensure table uses full width on every draw
    $(this.api().table().node()).css('width', '100%');
    $('.dataTables_wrapper').css('width', '100%');
}
</script> 