<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* White backgrounds for location tracking content */
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

.alert {
    background-image: none !important;
}

.alert-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
    background-image: none !important;
    border: 1px solid rgba(13, 202, 240, 0.2) !important;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="map-pin" class="icon-sm me-1"></i>
                    <?= lang('App.location_tracking_overview') ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshLocationTrackingTable" class="btn btn-outline-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i data-feather="info" class="icon-sm me-2"></i>
                    <?= lang('App.location_tracking_description') ?>
                </div>
                <div class="table-responsive">
                    <table id="locationTrackingTable" class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
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
function initLocationTrackingContent() {
    if (typeof $ === 'undefined') {
        setTimeout(initLocationTrackingContent, 100);
        return;
    }
    initializeLocationTrackingTable();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLocationTrackingContent);
} else {
    initLocationTrackingContent();
}

function initializeLocationTrackingTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initializeLocationTrackingTable, 100);
        return;
    }
    
    // Destroy existing table if it exists
    if ($.fn.DataTable.isDataTable('#locationTrackingTable')) {
        $('#locationTrackingTable').DataTable().destroy();
    }
    
    // Load data from server
    $.get('<?= base_url('vehicles/location-tracking-data') ?>')
        .done(function(response) {
            console.log('🔍 Location tracking response:', response);
            if (response.success && response.vehicles) {
                console.log(`✅ Found ${response.vehicles.length} vehicles with location data`);
                initializeLocationTableWithData(response.vehicles);
                
                // Update badge
                if (typeof updateBadge === 'function') {
                    updateBadge('locationTrackingBadge', response.count || response.vehicles.length);
                }
            } else {
                console.error('❌ Failed to load location tracking data:', response);
                if (response.debug) {
                    console.error('🐛 Debug info:', response.debug);
                }
                if (response.message) {
                    console.log('ℹ️ Info:', response.message);
                }
                initializeLocationTableWithData([]);
                
                // Update badge to 0
                if (typeof updateBadge === 'function') {
                    updateBadge('locationTrackingBadge', 0);
                }
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Failed to load location tracking data:', error);
            initializeLocationTableWithData([]);
        });
}

function initializeLocationTableWithData(data) {
    // Ensure VehiclesColumnHelpers is available
    if (typeof window.VehiclesColumnHelpers === 'undefined') {
        console.error('❌ VehiclesColumnHelpers not available');
        return;
    }
    
    window.locationTrackingTable = $('#locationTrackingTable').DataTable({
        processing: true,
        serverSide: false,
        data: data,
        columns: window.VehiclesColumnHelpers.generateStandardColumns('<?= base_url() ?>'),
        order: [[3, 'desc']], // Order by status desc (location tracking status)
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
            emptyTable: '<?= lang('App.no_location_tracking_data') ?>',
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
    $('#refreshLocationTrackingTable').off('click').on('click', function() {
        if (window.locationTrackingTable) {
            window.locationTrackingTable.destroy();
            initializeLocationTrackingTable();
        }
    });
}

// Global function to refresh this table
window.refreshLocationTrackingTable = function() {
    if (window.locationTrackingTable) {
        window.locationTrackingTable.destroy();
        initializeLocationTrackingTable();
    }
};

function viewLocationMap(vinLast6) {
    // Navigate to vehicle details page where the map would be shown
    window.location.href = `<?= base_url('vehicles/') ?>${vinLast6}#location-history`;
}

function exportLocationData(vinLast6) {
    // Trigger export of location data for this vehicle
    window.open(`<?= base_url('vehicles/export-data/') ?>${vinLast6}?type=locations`, '_blank');
}
</script> 