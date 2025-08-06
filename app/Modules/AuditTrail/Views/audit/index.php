<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.audit_trail') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.audit_trail') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.audit_trail') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.audit_trail') ?></h4>
        <div class="flex-shrink-0">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('audit/analytics') ?>" class="btn btn-outline-warning btn-sm">
                    <i data-feather="map" class="icon-sm me-1"></i>
                    Analytics
                </a>
                <button class="btn btn-outline-primary btn-sm" onclick="exportToPdf()">
                    <i data-feather="download" class="icon-sm me-1"></i>
                    PDF
                </button>
                <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                    <i data-feather="download" class="icon-sm me-1"></i>
                    Excel
                </button>
                <button class="btn btn-outline-info btn-sm" onclick="refreshTable()">
                    <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                    <?= lang('App.refresh') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filters Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.date_from') ?></label>
                <input type="date" id="dateFromFilter" name="date_from" class="form-control form-control-sm" 
                       value="<?= isset($filters['date_from']) ? esc($filters['date_from']) : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.date_to') ?></label>
                <input type="date" id="dateToFilter" name="date_to" class="form-control form-control-sm"
                       value="<?= isset($filters['date_to']) ? esc($filters['date_to']) : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.user') ?></label>
                <select id="userFilter" name="user_id" class="form-select form-select-sm">
                    <option value=""><?= lang('App.all') ?> <?= lang('App.users') ?></option>
                    <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= (isset($filters['user_id']) && $filters['user_id'] == $user['id']) ? 'selected' : '' ?>>
                                <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.actions') ?></label>
                <select id="actionFilter" name="action" class="form-select form-select-sm">
                    <option value=""><?= lang('App.all') ?> <?= lang('App.actions') ?></option>
                    <?php if (isset($actions) && !empty($actions)): ?>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?= $action['action'] ?>" <?= (isset($filters['action']) && $filters['action'] == $action['action']) ? 'selected' : '' ?>>
                                <?= esc($action['action']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.module') ?></label>
                <select id="moduleFilter" name="module" class="form-select form-select-sm">
                    <option value=""><?= lang('App.all') ?> <?= lang('App.modules') ?></option>
                    <?php if (isset($modules) && !empty($modules)): ?>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?= $module['module'] ?>" <?= (isset($filters['module']) && $filters['module'] == $module['module']) ? 'selected' : '' ?>>
                                <?= esc($module['module']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                    <i data-feather="filter" class="icon-sm me-1"></i>
                    <?= lang('App.apply_filters') ?>
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                    <i data-feather="x" class="icon-sm me-1"></i>
                    <?= lang('App.clear_filters') ?>
                </button>
            </div>
        </div>

        <!-- Audit Trail Table -->
        <div class="table-responsive">
            <table id="auditTable" class="table table-striped table-hover">
                <thead class="bg-light">
                    <tr>
                        <th><?= lang('App.date') ?></th>
                        <th><?= lang('App.user') ?></th>
                        <th><?= lang('App.action') ?></th>
                        <th><?= lang('App.module') ?></th>
                        <th><?= lang('App.record_id') ?></th>
                        <th><?= lang('App.description') ?></th>
                        <th><?= lang('App.ip_address') ?></th>
                        <th><?= lang('App.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($auditRecords) && !empty($auditRecords)): ?>
                        <?php foreach ($auditRecords as $record): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', strtotime($record['created_at'])) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        $avatarUrl = base_url('assets/images/users/avatar-1.jpg');
                                        if (!empty($record['avatar'])) {
                                            $avatarUrl = base_url('assets/images/users/' . $record['avatar']);
                                        }
                                        ?>
                                        <img src="<?= $avatarUrl ?>" 
                                             alt="User Avatar" class="rounded-circle me-2" width="24" height="24">
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                <?= esc($record['first_name'] . ' ' . ($record['last_name'] ?? '')) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php if (!empty($record['group_name'])): ?>
                                                    <?= esc($record['group_name']) ?>
                                                <?php elseif (!empty($record['role_title'])): ?>
                                                    <?= esc($record['role_title']) ?>
                                                <?php else: ?>
                                                    <?= esc($record['user_type'] ?? 'User') ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'bg-secondary';
                                    switch(strtoupper($record['action'])) {
                                        case 'CREATE':
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'UPDATE':
                                            $badgeClass = 'bg-warning';
                                            break;
                                        case 'DELETE':
                                            $badgeClass = 'bg-danger';
                                            break;
                                        case 'LOGIN':
                                            $badgeClass = 'bg-info';
                                            break;
                                        case 'LOGOUT':
                                            $badgeClass = 'bg-dark';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($record['action']) ?></span>
                                </td>
                                <td><?= esc($record['module']) ?></td>
                                <td><?= esc($record['record_id'] ?? '-') ?></td>
                                <td><?= esc($record['description']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        $deviceInfo = getDeviceInfo($record['user_agent'] ?? '');
                                        $browserInfo = getBrowserInfo($record['user_agent'] ?? '');
                                        
                                        // Get location info (use existing if available, otherwise detect from IP)
                                        if (!empty($record['country']) || !empty($record['city'])) {
                                            $locationInfo = [
                                                'country' => $record['country'],
                                                'country_code' => $record['country_code'],
                                                'city' => $record['city'],
                                                'flag_emoji' => getCountryFlag($record['country_code'] ?? ''),
                                                'display_name' => trim(($record['city'] ?? '') . ((!empty($record['city']) && !empty($record['country'])) ? ', ' : '') . ($record['country'] ?? '')) ?: 'Unknown Location'
                                            ];
                                        } else {
                                            $locationInfo = getLocationFromIP($record['ip_address'] ?? '');
                                        }
                                        ?>
                                        <div class="me-2">
                                            <i data-feather="<?= $deviceInfo['icon'] ?>" class="icon-xs <?= $deviceInfo['class'] ?>" 
                                               title="<?= $deviceInfo['label'] ?> - <?= $browserInfo['name'] ?>"></i>
                                        </div>
                                        <div class="me-2">
                                            <span title="<?= esc($locationInfo['display_name']) ?>" style="font-size: 14px;">
                                                <?= $locationInfo['flag_emoji'] ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($record['ip_address'] ?? '-') ?></div>
                                            <small class="text-muted">
                                                <?= $deviceInfo['label'] ?>
                                                <?php if (!empty($locationInfo['display_name']) && $locationInfo['display_name'] !== 'Unknown Location'): ?>
                                                    • <?= esc($locationInfo['display_name']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= base_url('audit/' . $record['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="eye" class="icon-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <?= lang('App.no_audit_records_found') ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <?php if (isset($totalRecords) && $totalRecords > 0): ?>
                    <small class="text-muted">
                        Showing <?= ($currentPage - 1) * $perPage + 1 ?> to <?= min($currentPage * $perPage, $totalRecords) ?> of <?= $totalRecords ?> entries
                    </small>
                <?php else: ?>
                    <small class="text-muted">No records found</small>
                <?php endif; ?>
            </div>
            <nav aria-label="Audit trail pagination">
                <?php if (isset($totalRecords) && $totalRecords > $perPage): ?>
                    <?php 
                    $totalPages = ceil($totalRecords / $perPage);
                    $currentFilters = http_build_query($filters);
                    $baseUrl = base_url('audit');
                    ?>
                    <ul class="pagination pagination-sm mb-0">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a href="<?= $baseUrl ?>?<?= $currentFilters ?>&page=<?= $currentPage - 1 ?>" class="page-link">‹</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a href="<?= $baseUrl ?>?<?= $currentFilters ?>&page=<?= $i ?>" class="page-link"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a href="<?= $baseUrl ?>?<?= $currentFilters ?>&page=<?= $currentPage + 1 ?>" class="page-link">›</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>

<script>
function exportToPdf() {
    // Show loading toast
    showToast('info', 'Exporting to PDF...', 'Please wait while we generate your report');
    
    fetch('<?= base_url('audit/export/pdf') ?>')
        .then(response => response.json())
        .then(data => {
            showToast('success', 'Export Status', data.message);
        })
        .catch(error => {
            showToast('error', 'Export Error', 'Failed to export to PDF');
            console.error('Export error:', error);
        });
}

function exportToExcel() {
    // Show loading toast
    showToast('info', 'Exporting to Excel...', 'Please wait while we generate your report');
    
    fetch('<?= base_url('audit/export/excel') ?>')
        .then(response => response.json())
        .then(data => {
            showToast('success', 'Export Status', data.message);
        })
        .catch(error => {
            showToast('error', 'Export Error', 'Failed to export to Excel');
            console.error('Export error:', error);
        });
}

function refreshTable() {
    showToast('info', 'Refreshing...', 'Loading latest audit records');
    // TODO: Implement table refresh
    setTimeout(() => {
        showToast('success', 'Refreshed', 'Audit trail has been updated');
    }, 1000);
}

function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '<?= base_url('audit') ?>';
    
    const dateFrom = document.getElementById('dateFromFilter').value;
    const dateTo = document.getElementById('dateToFilter').value;
    const user = document.getElementById('userFilter').value;
    const action = document.getElementById('actionFilter').value;
    const module = document.getElementById('moduleFilter').value;
    
    if (dateFrom) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'date_from';
        input.value = dateFrom;
        form.appendChild(input);
    }
    
    if (dateTo) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'date_to';
        input.value = dateTo;
        form.appendChild(input);
    }
    
    if (user) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_id';
        input.value = user;
        form.appendChild(input);
    }
    
    if (action) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'action';
        input.value = action;
        form.appendChild(input);
    }
    
    if (module) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'module';
        input.value = module;
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    window.location.href = '<?= base_url('audit') ?>';
}

function showToast(type, title, message) {
    // TODO: Implement toast notification system
    console.log(`${type.toUpperCase()}: ${title} - ${message}`);
}
</script>
<?= $this->endSection() ?>