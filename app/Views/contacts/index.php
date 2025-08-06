<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.contact_list') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.contact_list') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.contacts') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="contactList">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.contacts') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm me-2">
                        <i class="ri-refresh-line me-1"></i> 
                        <?= lang('App.refresh') ?>
                    </button>
                    <a href="<?= base_url('contacts/create') ?>" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i> <?= lang('App.create_contact') ?>
                    </a>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.search') ?></label>
                        <div class="search-box">
                            <input type="text" class="form-control search" placeholder="<?= lang('App.search_contacts') ?>...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_status') ?></label>
                        <select class="form-select" id="statusFilter">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="inactive"><?= lang('App.inactive') ?></option>
                        </select>
                    </div>
                    
                    <div class="col-xl-5 col-lg-12">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" onclick="SearchData();">
                                <i class="ri-search-line me-1"></i> <?= lang('App.apply_filters') ?>
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="clearFilters();">
                                <i class="ri-close-line me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="contacts-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center"><?= lang('App.contact') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.client') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.contact_info') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.primary_contact') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.status') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán vía AJAX -->
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
    console.log('🎯 Contacts List - Velzon Theme Loaded');
    
    let contactsTable;
    let isInitializing = false;
    
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
            if ($.fn.DataTable.isDataTable('#contacts-table')) {
                console.log('🔄 Destroying existing DataTable...');
                $('#contacts-table').DataTable().destroy();
                $('#contacts-table').off();
            }
            
            // Force table width before initialization
            $('#contacts-table').css('width', '100%');
            $('.table-responsive').css('width', '100%');
            
            contactsTable = $('#contacts-table').DataTable({
                ajax: {
                    url: '<?= base_url('contacts/getContactsData') ?>',
                    type: 'GET',
                    dataSrc: 'data'
                },
                responsive: false,
                scrollX: false,
                autoWidth: false,
                columnDefs: [
                    { width: "20%", targets: 0, className: "text-start" }, // Contact
                    { width: "20%", targets: 1, className: "text-start" }, // Client
                    { width: "25%", targets: 2, className: "text-start" }, // Contact Info
                    { width: "15%", targets: 3, className: "text-center" }, // Primary
                    { width: "10%", targets: 4, className: "text-center" }, // Status
                    { width: "10%", targets: 5, orderable: false, searchable: false, className: "text-center" } // Actions
                ],
                columns: [
                    { 
                        data: 'name',
                        render: function(data, type, row) {
                            // Extract contact ID from the actions HTML if row.id is not available
                            let contactId = row.id;
                            if (!contactId && row.actions) {
                                const match = row.actions.match(/contacts\/show\/(\d+)/);
                                contactId = match ? match[1] : '';
                            }
                            
                            if (contactId) {
                                return `<a href="<?= base_url('contacts/') ?>${contactId}" class="fw-medium text-primary text-decoration-none">${data}</a>`;
                            }
                            return `<span class="fw-medium">${data}</span>`;
                        }
                    },
                    { 
                        data: 'client_name',
                        render: function(data, type, row) {
                            if (data && data !== 'N/A') {
                                return `<span class="text-reset">${data}</span>`;
                            }
                            return '<span class="text-muted">---</span>';
                        }
                    },
                    { 
                        data: 'contact_info',
                        render: function(data, type, row) {
                            return data || '<span class="text-muted">---</span>';
                        }
                    },
                    { 
                        data: 'is_primary',
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data, type, row) {
                            // If data contains HTML, extract the text and apply proper styling
                            if (data.includes('status-indicator')) {
                                return data; // Already has correct styling
                            } else if (data.includes('Active') || data.includes('active')) {
                                return '<span class="status-indicator status-active"><?= lang('App.active') ?></span>';
                            } else if (data.includes('Inactive') || data.includes('inactive')) {
                                return '<span class="status-indicator status-inactive"><?= lang('App.inactive') ?></span>';
                            }
                            return data;
                        },
                        orderable: false
                    },
                    { 
                        data: 'actions',
                        render: function(data, type, row) {
                            // Extract contact ID from the actions HTML
                            const match = data.match(/contacts\/(\d+)/);
                            const contactId = match ? match[1] : '';
                            
                            return `
                                <div class="hstack gap-2 flex-wrap justify-content-center">
                                    <a href="<?= base_url('contacts/') ?>${contactId}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view') ?>">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="<?= base_url('contacts/edit/') ?>${contactId}" class="link-success fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">
                                        <i class="ri-edit-2-line"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="deleteContact(${contactId})" class="link-danger fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
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
                    showToast('success', '<?= lang('App.contacts_loaded_successfully') ?>');
                }
            });
            
        } catch (error) {
            console.error('❌ Error initializing DataTable:', error);
            isInitializing = false;
            showToast('error', '<?= lang('App.error_loading_contacts') ?>');
        }
    }
    
    // Initialize DataTable
    initializeDataTable();
    
    // Refresh button
    $('#refreshTable').on('click', function() {
        console.log('🔄 Refreshing table...');
        if (contactsTable) {
            contactsTable.ajax.reload();
            showToast('info', '<?= lang('App.table_refreshed') ?>');
        }
    });
    
    // Search functionality
    $('.search').on('keyup', function() {
        if (contactsTable) {
            contactsTable.search(this.value).draw();
        }
    });
    
    // Status filter
    $('#statusFilter').on('change', function() {
        const status = this.value;
        if (contactsTable) {
            if (status) {
                contactsTable.column(4).search(status).draw();
            } else {
                contactsTable.column(4).search('').draw();
            }
        }
    });
    
    // Apply filters function
    window.SearchData = function() {
        if (contactsTable) {
            contactsTable.draw();
            showToast('info', '<?= lang('App.filters_applied') ?>');
        }
    };
    
    // Clear filters function
    window.clearFilters = function() {
        $('.search').val('');
        $('#statusFilter').val('');
        if (contactsTable) {
            contactsTable.search('').columns().search('').draw();
            showToast('info', '<?= lang('App.filters_cleared') ?>');
        }
    };
});

// Delete Contact Function
function deleteContact(contactId) {
    Swal.fire({
        title: '<?= lang('App.are_you_sure') ?>',
        text: '<?= lang('App.delete_contact_warning') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('App.yes_delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send delete request
            fetch('<?= base_url('contacts/delete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: contactId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Deleted!', data.message, 'success');
                    if (contactsTable) {
                        contactsTable.ajax.reload();
                    }
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
