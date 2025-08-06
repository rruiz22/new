<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>App.staff_list<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.staff_list<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.staff<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.staff') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm me-2">
                        <i class="ri-refresh-line me-1"></i> 
                        <?= lang('App.refresh') ?>
                    </button>
                    <a href="<?= base_url('staff/create') ?>" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i> 
                        <?= lang('App.create_staff') ?>
                    </a>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_status') ?></label>
                        <select id="statusFilter" class="form-select">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="inactive"><?= lang('App.inactive') ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <button id="applyFilters" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i> <?= lang('App.apply_filters') ?>
                            </button>
                            <button id="clearFilters" class="btn btn-secondary">
                                <i class="ri-close-line me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="staff-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center"><?= lang('App.name') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.email') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.role') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.client') ?></th>
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
    console.log('🎯 Staff List - Velzon Theme Loaded');
    
    let staffTable;
    let isInitializing = false;
    
    // Prepare staff data for DataTable
    const staffData = <?= json_encode($staffUsers ?? []) ?>;
    
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
            if ($.fn.DataTable.isDataTable('#staff-table')) {
                console.log('🔄 Destroying existing DataTable...');
                $('#staff-table').DataTable().destroy();
                $('#staff-table').off();
            }
            
            // Force table width before initialization
            $('#staff-table').css('width', '100%');
            $('.table-responsive').css('width', '100%');
            
            staffTable = $('#staff-table').DataTable({
                data: staffData,
                responsive: false,
                scrollX: false,
                autoWidth: false,
                columnDefs: [
                    { width: "20%", targets: 0, className: "text-start" }, // Name
                    { width: "25%", targets: 1, className: "text-start" }, // Email
                    { width: "15%", targets: 2, className: "text-center" }, // Role
                    { width: "20%", targets: 3, className: "text-start" }, // Client
                    { width: "10%", targets: 4, className: "text-center" }, // Status
                    { width: "10%", targets: 5, orderable: false, searchable: false, className: "text-center" } // Actions
                ],
                columns: [
                    { 
                        data: null,
                        render: function(data, type, row) {
                            const fullName = `${row.first_name || ''} ${row.last_name || ''}`.trim();
                            const displayName = fullName || row.username;
                            return `<a href="<?= base_url('staff/edit/') ?>${row.id}" class="fw-medium text-primary text-decoration-none">${displayName}</a>`;
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
                        data: null,
                        render: function(data, type, row) {
                            if (row.role_title) {
                                const roleColor = row.role_color || '#6c757d';
                                return `<span class="badge" style="background-color: ${roleColor}; color: white;">${row.role_title}</span>`;
                            }
                            return '<span class="text-muted">No Role</span>';
                        }
                    },
                    { 
                        data: 'client_name',
                        render: function(data, type, row) {
                            if (data && data !== '') {
                                return `<span class="text-reset">${data}</span>`;
                            }
                            return '<span class="text-muted">---</span>';
                        }
                    },
                    { 
                        data: 'active',
                        render: function(data, type, row) {
                            const statusClass = data == 1 ? 'status-active' : 'status-inactive';
                            const statusText = data == 1 ? '<?= lang('App.active') ?>' : '<?= lang('App.inactive') ?>';
                            
                            return `<span class="status-indicator ${statusClass}">${statusText}</span>`;
                        },
                        orderable: false
                    },
                    { 
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="hstack gap-2 flex-wrap justify-content-center">
                                    <a href="<?= base_url('staff/edit/') ?>${data}" class="link-success fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">
                                        <i class="ri-edit-2-line"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="deleteStaff(${data}, '${row.first_name} ${row.last_name}')" class="link-danger fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
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
                    showToast('success', '<?= lang('App.staff_loaded_successfully') ?>');
                }
            });
            
        } catch (error) {
            console.error('❌ Error initializing DataTable:', error);
            isInitializing = false;
            showToast('error', '<?= lang('App.error_loading_staff') ?>');
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
        
        if (staffTable) {
            // Apply status filter
            staffTable.column(4).search(statusFilter).draw();
            showToast('info', '<?= lang('App.filters_applied') ?>');
        }
    });
    
    // Clear Filters
    $('#clearFilters').on('click', function() {
        $('#statusFilter').val('');
        
        if (staffTable) {
            staffTable.search('').columns().search('').draw();
            showToast('info', '<?= lang('App.filters_cleared') ?>');
        }
    });
});

// Delete Staff Function
function deleteStaff(staffId, staffName) {
    Swal.fire({
        title: '<?= lang('App.are_you_sure') ?>',
        text: `<?= lang('App.delete_staff_warning') ?> "${staffName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('App.yes_delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send delete request
            fetch(`<?= base_url('staff/ajax-delete/') ?>${staffId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Deleted!', data.message, 'success');
                    location.reload();
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            });
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
<?= $this->endSection() ?>
