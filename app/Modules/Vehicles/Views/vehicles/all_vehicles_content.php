<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* White backgrounds for all vehicles content */
.card {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

.card-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}

.card-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.modal-content {
    background-color: #ffffff !important;
    background-image: none !important;
}

.modal-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}

.modal-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.modal-footer {
    background-color: #ffffff !important;
    background-image: none !important;
    border-top: 1px solid #e9ecef !important;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="list" class="icon-sm me-1"></i>
                    <?= lang('App.all_vehicles_registry') ?>
                </h4>
                <div class="flex-shrink-0">
                    <div class="d-flex align-items-center gap-2">
                        <button id="refreshAllVehiclesTable" class="btn btn-outline-secondary btn-sm">
                            <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                            <?= lang('App.refresh') ?>
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="exportVehicles()">
                            <i data-feather="download" class="icon-sm me-1"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.export') ?></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="allVehiclesTable" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.vehicle_info') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
                                <th scope="col"><?= lang('App.service_history') ?></th>
                                <th scope="col"><?= lang('App.status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initAllVehiclesContent() {
    if (typeof $ === 'undefined') {
        setTimeout(initAllVehiclesContent, 100);
        return;
    }
    initializeAllVehiclesTable();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllVehiclesContent);
} else {
    initAllVehiclesContent();
}

function initializeAllVehiclesTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initializeAllVehiclesTable, 100);
        return;
    }
    
    // Destroy existing table if it exists
    if ($.fn.DataTable.isDataTable('#allVehiclesTable')) {
        $('#allVehiclesTable').DataTable().destroy();
    }
    
    // Try to load data from server, fallback to sample data
    $.get('<?= base_url('vehicles/all-vehicles-data') ?>')
        .done(function(response) {
            console.log('🔍 Server response received:', response);
            if (response.success && response.vehicles) {
                console.log(`✅ Found ${response.vehicles.length} vehicles from server`);
                console.log('🚗 First vehicle sample:', response.vehicles[0]);
                initializeTableWithData(response.vehicles);
            } else {
                console.error('❌ Failed response from server:', response);
                if (response.debug) {
                    console.error('🐛 Debug info:', response.debug);
                }
                initializeTableWithData([]);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Failed to load vehicles data:', error);
            console.warn('Vehicles endpoint not available, using sample data');
            initializeTableWithData(generateSampleVehiclesData());
        });
}

function initializeTableWithData(data) {
    // Ensure VehiclesColumnHelpers is available
    if (typeof window.VehiclesColumnHelpers === 'undefined') {
        console.error('❌ VehiclesColumnHelpers not available');
        return;
    }
    
    window.allVehiclesTable = $('#allVehiclesTable').DataTable({
        processing: true,
        serverSide: false,
        data: data,
        columns: window.VehiclesColumnHelpers.generateStandardColumns('<?= base_url() ?>'),
        order: [[2, 'desc']], // Sort by service history (total services)
        pageLength: 25,
        responsive: false,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            { width: "30%", targets: 0, className: 'vehicle-info-col' }, // Vehicle Info
            { width: "20%", targets: 1, className: 'client-col' }, // Client
            { width: "30%", targets: 2, className: 'service-history-col' }, // Service History
            { width: "20%", targets: 3, className: 'status-col' }  // Status
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            search: "<?= lang('App.search_vehicles') ?>:",
            lengthMenu: "<?= lang('App.show') ?> _MENU_ <?= lang('App.vehicles_per_page') ?>",
            info: "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.vehicles') ?>",
            infoEmpty: "<?= lang('App.no_vehicles_found') ?>",
            infoFiltered: "(<?= lang('App.filtered_from') ?> _MAX_ <?= lang('App.total_vehicles') ?>)",
            emptyTable: `<div class="text-center py-4">
                <i data-feather="truck" class="icon-lg text-muted mb-2"></i>
                <h6 class="mt-2"><?= lang('App.no_vehicles_found') ?></h6>
                <p class="text-muted"><?= lang('App.vehicles_will_appear_when_created') ?></p>
            </div>`,
            zeroRecords: "<?= lang('App.no_matching_vehicles_found') ?>",
            processing: `<div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                </div>
            </div>`
        },
        drawCallback: function() {
            // Execute standard callback first
            window.VehiclesColumnHelpers.standardDrawCallback.call(this);
            
            // Force table and wrapper to use exact width
            const $table = $(this.api().table().node());
            const $wrapper = $table.closest('.dataTables_wrapper');
            
            // Set exact widths
            $table.css({
                'width': '100%',
                'table-layout': 'fixed'
            });
            
            $wrapper.css('width', '100%');
            $wrapper.find('.dataTables_scroll').css('width', '100%');
            $wrapper.find('.dataTables_scrollBody').css('width', '100%');
            $wrapper.find('.dataTables_scrollHead').css('width', '100%');
            $wrapper.find('.dataTables_scrollHeadInner').css('width', '100%');
            $wrapper.find('.dataTables_scrollHeadInner table').css('width', '100%');
            
            // Force column widths directly
            $table.find('thead th:eq(0), tbody td:eq(0)').css({
                'width': '30%',
                'max-width': '30%'
            });
            $table.find('thead th:eq(1), tbody td:eq(1)').css({
                'width': '20%', 
                'max-width': '20%'
            });
            $table.find('thead th:eq(2), tbody td:eq(2)').css({
                'width': '30%',
                'max-width': '30%'
            });
            $table.find('thead th:eq(3), tbody td:eq(3)').css({
                'width': '20%',
                'max-width': '20%'
            });
            
            // Remove any extra spacing
            $table.find('thead th, tbody td').each(function() {
                $(this).css({
                    'box-sizing': 'border-box',
                    'overflow': 'hidden'
                });
            });
        }
    });

    // Refresh button functionality
    $('#refreshAllVehiclesTable').on('click', function() {
        if (window.allVehiclesTable) {
            window.allVehiclesTable.destroy();
            initializeAllVehiclesTable();
        }
    });
}

// Global function to refresh this table
window.refreshAllVehiclesTable = function() {
    if (window.allVehiclesTable) {
        window.allVehiclesTable.destroy();
        initializeAllVehiclesTable();
    }
};

function generateSampleVehiclesData() {
    return [
        {
            vehicle: '2023 Toyota Camry',
            vin_number: 'JT2BF22K1X0123456',
            client_name: 'ABC Motors',
            make: 'Toyota',
            model: 'Camry',
            year: '2023',
            total_services: 5,
            first_service_date: '2023-06-15',
            first_order_number: 'SO-2023-001',
            last_service_date: '2024-01-15',
            last_order_number: 'SO-2024-001',
            location_tracking_count: 12
        },
        {
            vehicle: '2022 Honda Civic',
            vin_number: 'JHMFC2F5XMX456789',
            client_name: 'City Auto',
            make: 'Honda',
            model: 'Civic',
            year: '2022',
            total_services: 3,
            first_service_date: '2023-08-20',
            first_order_number: 'SO-2023-045',
            last_service_date: '2024-01-14',
            last_order_number: 'SO-2024-002',
            location_tracking_count: 0
        },
        {
            vehicle: '2024 Ford F-150',
            vin_number: '1FTFW1E85PFC789012',
            client_name: 'Fleet Services',
            make: 'Ford',
            model: 'F-150',
            year: '2024',
            total_services: 8,
            first_service_date: '2023-03-10',
            first_order_number: 'SO-2023-012',
            last_service_date: '2024-01-13',
            last_order_number: 'SO-2024-003',
            location_tracking_count: 25
        },
        {
            vehicle: '2021 BMW 320i',
            vin_number: 'WBA8E1C52MH345678',
            client_name: 'Premium Auto',
            make: 'BMW',
            model: '320i',
            year: '2021',
            total_services: 12,
            first_service_date: '2022-11-05',
            first_order_number: 'SO-2022-089',
            last_service_date: '2024-01-10',
            last_order_number: 'SO-2024-004',
            location_tracking_count: 8
        },
        {
            vehicle: '2023 Mercedes C-Class',
            vin_number: 'WDDGF4HB9PR901234',
            client_name: 'Luxury Motors',
            make: 'Mercedes',
            model: 'C-Class',
            year: '2023',
            total_services: 6,
            first_service_date: '2023-07-22',
            first_order_number: 'SO-2023-067',
            last_service_date: '2024-01-12',
            last_order_number: 'SO-2024-005',
            location_tracking_count: 15
        }
    ];
}

function showVehicleLocationHistory(vinLast6) {
    // Switch to location tracking tab and filter by vehicle
    if (typeof showTab === 'function') {
        showTab('location-tracking-tab');
        setTimeout(() => {
            if (window.filterLocationTrackingByVehicle) {
                window.filterLocationTrackingByVehicle(vinLast6);
            }
        }, 500);
    }
}

function generateNFCToken(vinLast6) {
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for NFC token generation');
        return;
    }
    
    // Generate NFC token for vehicle
    $.post('<?= base_url('vehicles/generate-nfc-token') ?>', {
        vin_last6: vinLast6
    })
    .done(function(response) {
        if (response.success) {
            showTokenModal(response.token, response.qr_url, vinLast6);
        } else {
            showToast('error', response.message || '<?= lang('App.error_generating_token') ?>');
        }
    })
    .fail(function() {
        showToast('error', '<?= lang('App.error_generating_token') ?>');
    });
}

function exportVehicleData(vinLast6) {
    window.open(`<?= base_url('vehicles/export-data/') ?>${vinLast6}`, '_blank');
}

function exportVehicles() {
    const filters = window.vehicleGlobalFilters || {};
    const params = new URLSearchParams(filters);
    window.open(`<?= base_url('vehicles/export-all') ?>?${params.toString()}`, '_blank');
}

function showTokenModal(token, qrUrl, vinLast6) {
    // Create and show modal with NFC token information
    const modalHtml = `
        <div class="modal fade" id="nfcTokenModal" tabindex="-1" aria-labelledby="nfcTokenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nfcTokenModalLabel">
                            <i data-feather="smartphone" class="icon-sm me-2"></i>
                            <?= lang('App.nfc_token_generated') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-4">
                            <img src="${qrUrl}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.vehicle_vin') ?>:</label>
                            <div class="vehicle-vin-code">${vinLast6}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.nfc_url') ?>:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= base_url('location/') ?>${token}" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('<?= base_url('location/') ?>${token}')">
                                    <i data-feather="copy" class="icon-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i data-feather="info" class="icon-sm me-2"></i>
                            <?= lang('App.nfc_token_instructions') ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= lang('App.close') ?>
                        </button>
                        <button type="button" class="btn btn-primary" onclick="printQRCode('${qrUrl}')">
                            <i data-feather="printer" class="icon-sm me-1"></i>
                            <?= lang('App.print_qr_code') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('nfcTokenModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body and show
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    if (typeof $ !== 'undefined') {
        $('#nfcTokenModal').modal('show');
    }
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('success', '<?= lang('App.copied_to_clipboard') ?>');
        }, function() {
            showToast('error', '<?= lang('App.failed_to_copy') ?>');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('success', '<?= lang('App.copied_to_clipboard') ?>');
        } catch (err) {
            showToast('error', '<?= lang('App.failed_to_copy') ?>');
        }
        document.body.removeChild(textArea);
    }
}

function printQRCode(qrUrl) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title><?= lang('App.nfc_qr_code') ?></title>
                <style>
                    body { text-align: center; font-family: Arial, sans-serif; }
                    img { max-width: 300px; margin: 20px; }
                </style>
            </head>
            <body>
                <h2><?= lang('App.vehicle_location_tracker') ?></h2>
                <img src="${qrUrl}" alt="QR Code">
                <p><?= lang('App.scan_to_track_location') ?></p>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function showToast(type, message) {
    // Toast notification function using Toastify (consistent with Service Orders module)
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
            backgroundColor: colors[type] || colors.info,
        }).showToast();
    } else {
        // Fallback to alert if Toastify is not available
        alert(message);
    }
}
</script> 