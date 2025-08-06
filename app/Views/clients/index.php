<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.client_list') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.client_list') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.clients') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.clients') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm me-2">
                        <i class="ri-refresh-line me-1"></i> 
                        <?= lang('App.refresh') ?>
                    </button>
                    <a href="<?= base_url('clients/create') ?>" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i> 
                        <?= lang('App.create_client') ?>
                    </a>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card-body border-bottom py-2">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                            <!-- Status Filter -->
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 text-muted small fw-normal"><?= lang('App.filter_by_status') ?>:</label>
                                <select id="statusFilter" class="form-select form-select-sm border-0 bg-light" style="min-width: 120px;">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="inactive"><?= lang('App.inactive') ?></option>
                        </select>
                </div>
                
                            <!-- Filter Actions -->
                            <div class="d-flex gap-1">
                                <button id="applyFilters" class="btn btn-sm btn-outline-primary border-0 px-3 py-1" style="font-size: 12px;">
                                    <i class="ri-search-line me-1" style="font-size: 12px;"></i><?= lang('App.apply_filters') ?>
                            </button>
                                <button id="clearFilters" class="btn btn-sm btn-outline-secondary border-0 px-3 py-1" style="font-size: 12px;">
                                    <i class="ri-close-line me-1" style="font-size: 12px;"></i><?= lang('App.clear_filters') ?>
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="clients-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center"><?= lang('App.client_name') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.email') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.phone') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.status') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTable -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables -->
<?= $this->include('partials/datatables-scripts') ?>

<script>
    $(document).ready(function() {
        console.log('🎯 Clients List - Velzon Theme Loaded');
        
        let clientsTable;
        let isInitializing = false;
        
        // Prepare clients data for DataTable
        const clientsData = <?= json_encode($clients ?? []) ?>;
        
        // DataTable Configuration
        function initializeDataTable() {
            if (isInitializing) {
                console.log('⏳ DataTable already initializing...');
                return;
            }
            
            console.log('🚀 Initializing DataTable...');
            isInitializing = true;
            
            try {
                // Destroy existing table if it exists
                if ($.fn.DataTable.isDataTable('#clients-table')) {
                    console.log('🔄 Destroying existing DataTable...');
                    $('#clients-table').DataTable().destroy();
                    $('#clients-table').off();
                }
                
                // Force table width before initialization
                $('#clients-table').css('width', '100%');
                $('.table-responsive').css('width', '100%');
                
                clientsTable = $('#clients-table').DataTable({
                    data: clientsData,
                    responsive: false,
                    scrollX: false,
                    autoWidth: false,
                    columnDefs: [
                        { width: "30%", targets: 0, className: "text-start" }, // Client Name
                        { width: "25%", targets: 1, className: "text-start" }, // Email
                        { width: "20%", targets: 2, className: "text-start" }, // Phone
                        { width: "10%", targets: 3, className: "text-center" }, // Status
                        { width: "15%", targets: 4, orderable: false, searchable: false, className: "text-center" } // Actions
                    ],
                    columns: [
                        { 
                            data: 'name',
                            render: function(data, type, row) {
                                return `<a href="<?= base_url('clients/') ?>${row.id}" class="fw-medium text-primary text-decoration-none">${data}</a>`;
                            }
                        },
                        { 
                            data: 'email',
                            render: function(data, type, row) {
                                if (data && data !== '') {
                                    return `<a href="mailto:${data}" class="text-reset">${data}</a>`;
                                }
                                return '<span class="text-muted">---</span>';
                            }
                        },
                        { 
                            data: 'phone',
                            render: function(data, type, row) {
                                if (data && data !== '') {
                                    return `<a href="tel:${data}" class="text-reset">${data}</a>`;
                                }
                                return '<span class="text-muted">---</span>';
                            }
                        },
                        { 
                            data: 'status',
                            render: function(data, type, row) {
                                const statusClass = data === 'active' ? 'status-active' : 'status-inactive';
                                const statusText = data === 'active' ? '<?= lang('App.active') ?>' : '<?= lang('App.inactive') ?>';
                                
                                return `<span class="status-indicator ${statusClass}">${statusText}</span>`;
                            },
                            orderable: false
                        },
                        { 
                            data: 'id',
                            render: function(data, type, row) {
                                return `
                                    <div class="hstack gap-2 flex-wrap justify-content-center">
                                        <a href="<?= base_url('clients/') ?>${data}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view') ?>">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="<?= base_url('clients/edit/') ?>${data}" class="link-success fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">
                                            <i class="ri-edit-2-line"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="deleteClient(${data})" class="link-danger fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
                                            <i class="ri-delete-bin-line"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    language: {
                        // Use the same fixed language configuration as datatables-settings.js
                        url: function() {
                            const currentLang = document.documentElement.lang || 
                                               localStorage.getItem('locale') || 
                                               '<?= session()->get('locale') ?? 'en' ?>';
                            
                            const baseUrl = window.baseUrl || '<?= base_url() ?>';
                            
                            const langMap = {
                                'es': baseUrl + 'assets/libs/datatables/i18n/es-ES.json',
                                'pt': baseUrl + 'assets/libs/datatables/i18n/pt-BR.json',
                                'en': baseUrl + 'assets/libs/datatables/i18n/en-US.json'
                            };
                            
                            return langMap[currentLang] || langMap['en'];
                        }()
                    },
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('App.all') ?>"]],
                    order: [[0, 'asc']],
                    drawCallback: function(settings) {
                        // Initialize tooltips after each draw
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    },
                    initComplete: function(settings, json) {
                        console.log('✅ DataTable initialized successfully');
                        isInitializing = false;
                        
                        // Initialize tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        
                        // Show success message
                        showToast('success', '<?= lang('App.clients_loaded_successfully') ?>');
                    }
                });
                
            } catch (error) {
                console.error('❌ Error initializing DataTable:', error);
                isInitializing = false;
                showToast('error', '<?= lang('App.error_loading_clients') ?>');
            }
        }
        
        // Initialize DataTable
        initializeDataTable();
        
        // Refresh Table
        $('#refreshTable').on('click', function() {
            console.log('🔄 Refreshing table...');
            location.reload();
        });
        
        // Apply Filters
        $('#applyFilters').on('click', function() {
            const statusFilter = $('#statusFilter').val();
            console.log('Applying filter with value:', statusFilter);
            
            if (clientsTable) {
                if (statusFilter === '') {
                    // Clear all custom filters
                    if ($.fn.dataTable.ext.search.length > 0) {
                        $.fn.dataTable.ext.search.pop();
                    }
                    clientsTable.draw();
                } else {
                    // Remove any existing custom filter
                    if ($.fn.dataTable.ext.search.length > 0) {
                        $.fn.dataTable.ext.search.pop();
                    }
                    
                    // Add custom filter function
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            // Get the original row data
                            const rowData = clientsTable.row(dataIndex).data();
                            
                            // Compare with original status value
                            return rowData.status === statusFilter;
                        }
                    );
                    
                    console.log('Applied custom filter for status:', statusFilter);
                    clientsTable.draw();
                }
                showToast('info', '<?= lang('App.filters_applied') ?>');
            }
        });
        
        // Clear Filters
        $('#clearFilters').on('click', function() {
            console.log('Clearing all filters');
            
            // Reset select value
            $('#statusFilter').val('');
            
            if (clientsTable) {
                // Remove any custom filters
                while ($.fn.dataTable.ext.search.length > 0) {
                    $.fn.dataTable.ext.search.pop();
                }
                
                // Clear column searches and global search
                clientsTable.search('').columns().search('').draw();
                showToast('info', '<?= lang('App.filters_cleared') ?>');
            }
        });
    });
    
    // Delete Client Function
    function deleteClient(clientId) {
        console.log('deleteClient called with ID:', clientId);
        
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.delete_client_warning') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('User confirmed deletion');
                
                // Show loading
                Swal.fire({
                    title: '<?= lang('App.processing') ?>',
                    text: '<?= lang('App.please_wait') ?>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Prepare FormData
                const formData = new FormData();
                formData.append('id', clientId);
                
                console.log('Sending FormData with ID:', clientId);
                
                // Send delete request using FormData
                fetch('<?= base_url('clients/delete') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response received:', data);
                    
                    if (data.status) {
                        Swal.fire({
                            title: '<?= lang('App.success') ?>',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: '<?= lang('App.ok') ?>'
                        }).then(() => {
                        location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: '<?= lang('App.error') ?>',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: '<?= lang('App.ok') ?>'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: '<?= lang('App.error') ?>',
                        text: '<?= lang('App.something_wrong') ?>',
                        icon: 'error',
                        confirmButtonText: '<?= lang('App.ok') ?>'
                    });
                });
            } else {
                console.log('User cancelled deletion');
            }
        });
    }

    // Toast function
    function showToast(type, message) {
        if (typeof Toastify !== 'undefined') {
            const colors = {
                success: "#28a745",
                error: "#dc3545", 
                info: "#17a2b8",
                warning: "#ffc107"
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: colors[type] || colors.info
                },
            }).showToast();
        }
    }
</script>

<!-- Estilos minimalistas para filtros -->
<style>
    /* Filtros minimalistas */
    .card-body.border-bottom {
        background-color: #fafbfc;
    }
    
    /* Select personalizado */
    #statusFilter {
        border-radius: 6px !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
        font-size: 13px;
    }
    
    #statusFilter:focus {
        background-color: #fff !important;
        border: 1px solid #e3e6f0 !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1) !important;
    }
    
    #statusFilter:hover {
        background-color: #fff !important;
    }
    
    /* Botones minimalistas */
    #applyFilters, #clearFilters {
        border-radius: 6px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
        background-color: transparent !important;
    }
    
    #applyFilters:hover {
        background-color: rgba(13, 110, 253, 0.1) !important;
        color: #0d6efd !important;
        transform: translateY(-1px);
    }
    
    #clearFilters:hover {
        background-color: rgba(108, 117, 125, 0.1) !important;
        color: #6c757d !important;
        transform: translateY(-1px);
    }
    
    /* Label del filtro */
    .form-label.text-muted {
        font-size: 12px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    /* Responsive para móviles */
    @media (max-width: 768px) {
        .d-flex.justify-content-end {
            justify-content: center !important;
        }
        
        .d-flex.gap-2.flex-wrap {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem !important;
        }
        
        #statusFilter {
            min-width: 150px !important;
        }
    }
</style>
<?= $this->endSection() ?>