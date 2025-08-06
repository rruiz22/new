<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* White backgrounds for recent vehicles content */
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
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="clock" class="icon-sm me-1"></i>
                    <?= lang('App.recent_vehicles') ?> (<?= lang('App.last_30_days') ?>)
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshRecentVehiclesTable" class="btn btn-outline-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="recentVehiclesTable" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.vehicle_info') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
                                <th scope="col"><?= lang('App.service_history') ?></th>
                                <th scope="col"><?= lang('App.status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="text-center"><?= lang('App.loading') ?>...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initRecentVehiclesContent() {
    if (typeof $ === 'undefined') {
        setTimeout(initRecentVehiclesContent, 100);
        return;
    }
    initializeRecentVehiclesTable();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRecentVehiclesContent);
} else {
    initRecentVehiclesContent();
}

function initializeRecentVehiclesTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initializeRecentVehiclesTable, 100);
        return;
    }
    
    // Destroy existing table if it exists
    if ($.fn.DataTable.isDataTable('#recentVehiclesTable')) {
        $('#recentVehiclesTable').DataTable().destroy();
    }
    
    // Load data from server
    $.get('<?= base_url('vehicles/recent-vehicles-data') ?>')
        .done(function(response) {
            console.log('🔍 Recent vehicles response:', response);
            if (response.success && response.vehicles) {
                console.log(`✅ Found ${response.vehicles.length} recent vehicles`);
                initializeRecentTableWithData(response.vehicles);
                
                // Update badge
                if (typeof updateBadge === 'function') {
                    updateBadge('recentVehiclesBadge', response.count || response.vehicles.length);
                }
            } else {
                console.error('❌ Failed to load recent vehicles:', response);
                if (response.debug) {
                    console.error('🐛 Debug info:', response.debug);
                }
                initializeRecentTableWithData([]);
                
                // Update badge to 0
                if (typeof updateBadge === 'function') {
                    updateBadge('recentVehiclesBadge', 0);
                }
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Failed to load recent vehicles data:', error);
            initializeRecentTableWithData([]);
        });
}

function initializeRecentTableWithData(data) {
    // Ensure VehiclesColumnHelpers is available
    if (typeof window.VehiclesColumnHelpers === 'undefined') {
        console.error('❌ VehiclesColumnHelpers not available');
        return;
    }
    
    window.recentVehiclesTable = $('#recentVehiclesTable').DataTable({
        processing: true,
        serverSide: false,
        data: data,
        columns: window.VehiclesColumnHelpers.generateStandardColumns('<?= base_url() ?>'),
        order: [[3, 'desc']], // Order by status desc (recent items first)
        pageLength: 10,
        responsive: false,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            { width: "30%", targets: 0, className: 'vehicle-info-col' }, // Vehicle Info
            { width: "20%", targets: 1, className: 'client-col' }, // Client
            { width: "30%", targets: 2, className: 'service-history-col' }, // Service History
            { width: "20%", targets: 3, className: 'status-col' }  // Status
        ],
        language: {
            emptyTable: '<?= lang('App.no_recent_vehicles_found') ?>',
            info: '<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.vehicles') ?>',
            lengthMenu: '<?= lang('App.show') ?> _MENU_ <?= lang('App.vehicles') ?>',
            search: '<?= lang('App.search') ?>:',
            paginate: {
                first: '<?= lang('App.first') ?>',
                last: '<?= lang('App.last') ?>',
                next: '<?= lang('App.next') ?>',
                previous: '<?= lang('App.previous') ?>'
            }
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
    
    // Set up refresh button event
    $('#refreshRecentVehiclesTable').off('click').on('click', function() {
        if (window.recentVehiclesTable) {
            window.recentVehiclesTable.destroy();
            initializeRecentVehiclesTable();
        }
    });
}

// Global function to refresh this table
window.refreshRecentVehiclesTable = function() {
    if (window.recentVehiclesTable) {
        window.recentVehiclesTable.destroy();
        initializeRecentVehiclesTable();
    }
};

function showLocationHistory(vinLast6) {
    // Switch to location tracking tab and filter by specific vehicle
    const locationTab = document.querySelector('a[href="#location-tracking-tab"]');
    if (locationTab) {
        locationTab.click();
        // Could add filtering logic here
    }
}
</script> 