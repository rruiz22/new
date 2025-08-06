<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $page_title ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= $page_title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1"><?= $page_title ?></h4>
                <div class="flex-shrink-0">
                    <a href="<?= base_url('audit') ?>" class="btn btn-outline-secondary btn-sm">
                        <i data-feather="arrow-left" class="icon-sm me-1"></i>
                        <?= lang('App.back_to_list') ?>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Audit Record Details -->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Main Information -->
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="info" class="icon-sm me-2"></i>
                                    <?= lang('App.audit_details') ?>
                                </h5>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.date_time') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="text-muted">2025-06-11 12:30:45</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.user') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" 
                                                 alt="User Avatar" class="rounded-circle me-2" width="24" height="24">
                                            <span>Admin User</span>
                                            <span class="badge bg-success ms-2">Active</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.action') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-success fs-6">CREATE</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.module') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="text-primary fw-semibold">Sales Orders</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.record_id') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <code>SO-001</code>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= lang('App.description') ?>:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="mb-0">Created new sales order for client "ABC Company" with total amount $1,250.00. Order includes 3 services: Detail Wash, Interior Cleaning, and Wax Protection.</p>
                                    </div>
                                </div>

                                <?php if (isset($auditRecord)): ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong><?= lang('App.ip_address') ?>:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="d-flex align-items-center">
                                                <?php
                                                $deviceInfo = getDeviceInfo($auditRecord['user_agent'] ?? '');
                                                $browserInfo = getBrowserInfo($auditRecord['user_agent'] ?? '');
                                                $osInfo = getOSInfo($auditRecord['user_agent'] ?? '');
                                                
                                                // Get location info
                                                if (!empty($auditRecord['country']) || !empty($auditRecord['city'])) {
                                                    $locationInfo = [
                                                        'country' => $auditRecord['country'],
                                                        'country_code' => $auditRecord['country_code'],
                                                        'region' => $auditRecord['region'],
                                                        'city' => $auditRecord['city'],
                                                        'latitude' => $auditRecord['latitude'],
                                                        'longitude' => $auditRecord['longitude'],
                                                        'timezone' => $auditRecord['timezone'],
                                                        'flag_emoji' => getCountryFlag($auditRecord['country_code'] ?? ''),
                                                        'display_name' => trim(($auditRecord['city'] ?? '') . ((!empty($auditRecord['city']) && !empty($auditRecord['country'])) ? ', ' : '') . ($auditRecord['country'] ?? '')) ?: 'Unknown Location'
                                                    ];
                                                } else {
                                                    $locationInfo = getLocationFromIP($auditRecord['ip_address'] ?? '');
                                                }
                                                ?>
                                                <div class="me-3">
                                                    <i data-feather="<?= $deviceInfo['icon'] ?>" class="icon-sm <?= $deviceInfo['class'] ?>" 
                                                       title="<?= $deviceInfo['label'] ?>"></i>
                                                </div>
                                                <div class="me-3">
                                                    <span style="font-size: 20px;" title="<?= esc($locationInfo['display_name']) ?>">
                                                        <?= $locationInfo['flag_emoji'] ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <code class="fs-6"><?= esc($auditRecord['ip_address'] ?? '-') ?></code>
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-dark me-1"><?= $deviceInfo['label'] ?></span>
                                                        <span class="badge bg-light text-dark me-1"><?= $browserInfo['name'] ?></span>
                                                        <span class="badge bg-light text-dark me-1"><?= $osInfo ?></span>
                                                        <?php if (!empty($locationInfo['display_name']) && $locationInfo['display_name'] !== 'Unknown Location'): ?>
                                                            <span class="badge bg-primary text-white">
                                                                <i data-feather="map-pin" class="icon-xs me-1"></i>
                                                                <?= esc($locationInfo['display_name']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong><?= lang('App.user_agent') ?>:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <small class="text-muted font-monospace"><?= esc($auditRecord['user_agent'] ?? '-') ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong><?= lang('App.ip_address') ?>:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <code>-</code>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong><?= lang('App.user_agent') ?>:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <small class="text-muted">-</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Changes Details -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="edit" class="icon-sm me-2"></i>
                                    <?= lang('App.changes_made') ?>
                                </h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th><?= lang('App.field') ?></th>
                                                <th><?= lang('App.old_value') ?></th>
                                                <th><?= lang('App.new_value') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($auditRecord) && (!empty($auditRecord['old_values']) || !empty($auditRecord['new_values']))): ?>
                                                <?php
                                                $oldValues = !empty($auditRecord['old_values']) ? json_decode($auditRecord['old_values'], true) : [];
                                                $newValues = !empty($auditRecord['new_values']) ? json_decode($auditRecord['new_values'], true) : [];
                                                $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                                ?>
                                                <?php if (!empty($allFields)): ?>
                                                    <?php foreach ($allFields as $field): ?>
                                                        <tr>
                                                            <td><strong><?= esc(ucfirst(str_replace('_', ' ', $field))) ?></strong></td>
                                                            <td>
                                                                <?php if (isset($oldValues[$field])): ?>
                                                                    <span class="text-danger"><?= esc($oldValues[$field]) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if (isset($newValues[$field])): ?>
                                                                    <span class="text-success"><?= esc($newValues[$field]) ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-3">
                                                            No detailed changes recorded
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-3">
                                                        No changes data available
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="zap" class="icon-sm me-2"></i>
                                    <?= lang('App.quick_actions') ?>
                                </h5>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewRecord()">
                                        <i data-feather="external-link" class="icon-sm me-1"></i>
                                        <?= lang('App.view_record') ?>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="exportDetails()">
                                        <i data-feather="download" class="icon-sm me-1"></i>
                                        <?= lang('App.export_details') ?>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="printDetails()">
                                        <i data-feather="printer" class="icon-sm me-1"></i>
                                        <?= lang('App.print') ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Related Records -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="link" class="icon-sm me-2"></i>
                                    <?= lang('App.related_records') ?>
                                </h5>
                                
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <small class="text-muted">Previous Action</small>
                                            <div class="fw-semibold">User Login</div>
                                        </div>
                                        <a href="<?= base_url('audit/' . ($audit_id - 1)) ?>" class="btn btn-sm btn-outline-primary">
                                            <i data-feather="eye" class="icon-xs"></i>
                                        </a>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <small class="text-muted">Next Action</small>
                                            <div class="fw-semibold">Order Update</div>
                                        </div>
                                        <a href="<?= base_url('audit/' . ($audit_id + 1)) ?>" class="btn btn-sm btn-outline-primary">
                                            <i data-feather="eye" class="icon-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="map-pin" class="icon-sm me-2"></i>
                                    <?= lang('App.location_info') ?>
                                </h5>
                                
                                <div class="row g-3">
                                    <?php if (isset($auditRecord)): ?>
                                        <?php
                                        // Get location info
                                        if (!empty($auditRecord['country']) || !empty($auditRecord['city'])) {
                                            $locationInfo = [
                                                'country' => $auditRecord['country'],
                                                'country_code' => $auditRecord['country_code'],
                                                'region' => $auditRecord['region'],
                                                'city' => $auditRecord['city'],
                                                'latitude' => $auditRecord['latitude'],
                                                'longitude' => $auditRecord['longitude'],
                                                'timezone' => $auditRecord['timezone'],
                                                'flag_emoji' => getCountryFlag($auditRecord['country_code'] ?? ''),
                                                'display_name' => trim(($auditRecord['city'] ?? '') . ((!empty($auditRecord['city']) && !empty($auditRecord['country'])) ? ', ' : '') . ($auditRecord['country'] ?? '')) ?: 'Unknown Location'
                                            ];
                                        } else {
                                            $locationInfo = getLocationFromIP($auditRecord['ip_address'] ?? '');
                                        }
                                        ?>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <span style="font-size: 24px;" class="me-2"><?= $locationInfo['flag_emoji'] ?></span>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($locationInfo['display_name']) ?></div>
                                                    <?php if (!empty($locationInfo['timezone'])): ?>
                                                        <small class="text-muted"><?= esc($locationInfo['timezone']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($locationInfo['latitude']) && !empty($locationInfo['longitude'])): ?>
                                            <div class="col-12">
                                                <small class="text-muted"><?= lang('App.coordinates') ?></small>
                                                <div class="small font-monospace">
                                                    <?= formatCoordinates($locationInfo['latitude'], $locationInfo['longitude']) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <span class="text-muted">No location data available</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i data-feather="monitor" class="icon-sm me-2"></i>
                                    <?= lang('App.system_info') ?>
                                </h5>
                                
                                <div class="row g-3">
                                    <?php if (isset($auditRecord)): ?>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.session_id') ?></small>
                                            <div class="fw-mono small"><?= esc($auditRecord['session_id'] ?? '-') ?></div>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.request_id') ?></small>
                                            <div class="fw-mono small"><?= esc($auditRecord['request_id'] ?? '-') ?></div>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.server_time') ?></small>
                                            <div class="small"><?= date('Y-m-d H:i:s T', strtotime($auditRecord['created_at'])) ?></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.session_id') ?></small>
                                            <div class="fw-mono small">-</div>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.request_id') ?></small>
                                            <div class="fw-mono small">-</div>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted"><?= lang('App.server_time') ?></small>
                                            <div class="small">-</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewRecord() {
    // Redirect to the actual record
    showToast('info', 'Redirecting...', 'Opening the original record');
    // TODO: Implement redirect to actual record
}

function exportDetails() {
    showToast('info', 'Exporting...', 'Generating audit detail report');
    // TODO: Implement export functionality
}

function printDetails() {
    window.print();
}

function showToast(type, title, message) {
    // TODO: Implement toast notification system
    console.log(`${type.toUpperCase()}: ${title} - ${message}`);
}
</script>
<?= $this->endSection() ?>