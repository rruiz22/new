<?php
// Defensive programming: Ensure required variables exist with default values
$clients = $clients ?? [];
$services = $services ?? [];
$totalServices = $totalServices ?? count($services);

// Helper function to safely get array value
function safeGet($array, $key, $default = '') {
    if (!is_array($array)) {
        return $default;
    }
    return isset($array[$key]) && $array[$key] !== null ? $array[$key] : $default;
}

// Error handling - check if we have access to required functions
$hasLanguageSupport = function_exists('lang');
$hasEscapeSupport = function_exists('esc');

// Fallback functions if not available
if (!$hasLanguageSupport) {
    function lang($key) { return $key; }
}

if (!$hasEscapeSupport) {
    function esc($value) { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
}
?>

<!-- Services Tab Content -->
<div class="notion-card">
    <div class="notion-card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="notion-card-title"><?= lang('App.services') ?></h3>
            <div class="notion-card-actions">
                <button id="refreshServicesTable" class="notion-btn notion-btn-ghost notion-btn-sm">
                    <i data-feather="refresh-cw" class="notion-icon" aria-hidden="true"></i>
                    <span><?= lang('App.refresh') ?></span>
                </button>
                <button id="addServiceBtn" class="notion-btn notion-btn-primary" type="button">
                    <i data-feather="plus" class="notion-icon" aria-hidden="true"></i>
                    <span><?= lang('App.add_service') ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Services Filters -->
    <div class="notion-card-filters">
        <div class="notion-grid notion-grid-filters">
            <div class="notion-form-group">
                <label for="servicesClientFilter" class="notion-label"><?= lang('App.filter_by_client') ?></label>
                <select id="servicesClientFilter" class="notion-select">
                    <option value=""><?= lang('App.all_clients') ?></option>
                    <option value="general">General Services</option>
                    <?php if (isset($clients) && !empty($clients)): ?>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= esc(safeGet($client, 'id', '')) ?>">
                                <?= esc(safeGet($client, 'name') ?: safeGet($client, 'client_name') ?: 'Unknown Client') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="notion-form-group">
                <label for="servicesStatusFilter" class="notion-label"><?= lang('App.status') ?></label>
                <select id="servicesStatusFilter" class="notion-select">
                    <option value=""><?= lang('App.all_status') ?></option>
                    <option value="active"><?= lang('App.active') ?></option>
                    <option value="inactive"><?= lang('App.inactive') ?></option>
                </select>
            </div>

            <div class="notion-form-group">
                <label for="servicesTypeFilter" class="notion-label"><?= lang('App.service_type') ?></label>
                <select id="servicesTypeFilter" class="notion-select">
                    <option value=""><?= lang('App.all_types') ?></option>
                    <option value="maintenance">Maintenance</option>
                    <option value="repair">Repair</option>
                    <option value="inspection">Inspection</option>
                    <option value="cleaning">Cleaning</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <div class="notion-card-body">
        <div class="notion-table-container">
            <div class="table-responsive">
                <table class="notion-table" id="servicesTable">
                    <thead class="notion-table-header">
                        <tr>
                            <th class="notion-table-cell"><?= lang('App.service_name') ?></th>
                            <th class="notion-table-cell"><?= lang('App.client') ?></th>
                            <th class="notion-table-cell"><?= lang('App.description') ?></th>
                            <th class="notion-table-cell"><?= lang('App.price') ?></th>
                            <th class="notion-table-cell"><?= lang('App.duration') ?></th>
                            <th class="notion-table-cell"><?= lang('App.status') ?></th>
                            <th class="notion-table-cell notion-table-cell-actions"><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody class="notion-table-body" id="servicesTableBody">
                        <?php if (isset($services) && !empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <tr class="notion-table-row" data-service-id="<?= esc(safeGet($service, 'id', '0')) ?>">
                                    <td class="notion-table-cell">
                                        <div class="notion-cell-content">
                                            <strong><?= esc(safeGet($service, 'name') ?: safeGet($service, 'service_name') ?: 'Unnamed Service') ?></strong>
                                            <?php if (!empty(safeGet($service, 'code'))): ?>
                                                <small class="notion-text-secondary d-block"><?= esc(safeGet($service, 'code')) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="notion-table-cell">
                                        <span class="notion-badge notion-badge-secondary">
                                            <?= esc(safeGet($service, 'client_name') ?: safeGet($service, 'client') ?: 'General') ?>
                                        </span>
                                    </td>
                                    <td class="notion-table-cell">
                                        <div class="notion-text-truncate" title="<?= esc(safeGet($service, 'description', '')) ?>">
                                            <?= esc(safeGet($service, 'description') ?: 'No description') ?>
                                        </div>
                                    </td>
                                    <td class="notion-table-cell">
                                        <span class="notion-price">
                                            <?php 
                                                $price = safeGet($service, 'price') ?: safeGet($service, 'amount') ?: 0;
                                                $price = floatval($price);
                                            ?>
                                            $<?= number_format($price, 2) ?>
                                        </span>
                                    </td>
                                    <td class="notion-table-cell">
                                        <span class="notion-duration">
                                            <?= esc(safeGet($service, 'duration') ?: safeGet($service, 'estimated_duration') ?: '0') ?> min
                                        </span>
                                    </td>
                                    <td class="notion-table-cell">
                                        <?php $status = safeGet($service, 'status', 'active'); ?>
                                        <span class="notion-status notion-status-<?= esc($status) ?>">
                                            <?= ucfirst(esc($status)) ?>
                                        </span>
                                    </td>
                                    <td class="notion-table-cell notion-table-cell-actions">
                                        <div class="notion-actions-group">
                                            <?php $serviceId = safeGet($service, 'id', '0'); ?>
                                            <button class="notion-btn notion-btn-ghost notion-btn-sm" onclick="editService(<?= esc($serviceId) ?>)" title="<?= lang('App.edit') ?>">
                                                <i data-feather="edit-2" class="notion-icon" aria-hidden="true"></i>
                                            </button>
                                            <button class="notion-btn notion-btn-ghost notion-btn-sm" onclick="viewService(<?= esc($serviceId) ?>)" title="<?= lang('App.view') ?>">
                                                <i data-feather="eye" class="notion-icon" aria-hidden="true"></i>
                                            </button>
                                            <button class="notion-btn notion-btn-ghost notion-btn-sm notion-btn-danger" onclick="deleteService(<?= esc($serviceId) ?>)" title="<?= lang('App.delete') ?>">
                                                <i data-feather="trash-2" class="notion-icon" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="notion-table-row">
                                <td colspan="7" class="notion-table-cell">
                                    <div class="notion-empty-state">
                                        <i data-feather="package" class="notion-empty-icon" aria-hidden="true"></i>
                                        <h4><?= lang('App.no_services') ?></h4>
                                        <p><?= lang('App.no_services_message') ?></p>
                                        <button class="notion-btn notion-btn-primary" id="addFirstService">
                                            <i data-feather="plus" class="notion-icon" aria-hidden="true"></i>
                                            <span><?= lang('App.add_first_service') ?></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Services Pagination -->
        <?php if (isset($services) && count($services) > 25): ?>
            <div class="notion-pagination">
                <div class="notion-pagination-info">
                    <span class="notion-text-secondary">
                        Showing <?= count($services) ?> of <?= $totalServices ?? count($services) ?> services
                    </span>
                </div>
                <div class="notion-pagination-controls">
                    <button class="notion-btn notion-btn-ghost notion-btn-sm" id="prevServicesPage" disabled>
                        <i data-feather="chevron-left" class="notion-icon" aria-hidden="true"></i>
                        <span>Previous</span>
                    </button>
                    <span class="notion-pagination-current">Page 1 of 1</span>
                    <button class="notion-btn notion-btn-ghost notion-btn-sm" id="nextServicesPage" disabled>
                        <span>Next</span>
                        <i data-feather="chevron-right" class="notion-icon" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Services JavaScript -->
<script type="module">
class ServicesManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeFeatherIcons();
    }

    bindEvents() {
        // Filter events
        document.getElementById('servicesClientFilter')?.addEventListener('change', () => this.applyFilters());
        document.getElementById('servicesStatusFilter')?.addEventListener('change', () => this.applyFilters());
        document.getElementById('servicesTypeFilter')?.addEventListener('change', () => this.applyFilters());

        // Button events
        document.getElementById('addServiceBtn')?.addEventListener('click', () => this.openAddServiceModal());
        document.getElementById('addFirstService')?.addEventListener('click', () => this.openAddServiceModal());
        document.getElementById('refreshServicesTable')?.addEventListener('click', () => this.refreshTable());
    }

    applyFilters() {
        const clientFilter = document.getElementById('servicesClientFilter')?.value || '';
        const statusFilter = document.getElementById('servicesStatusFilter')?.value || '';
        const typeFilter = document.getElementById('servicesTypeFilter')?.value || '';

        const rows = document.querySelectorAll('#servicesTableBody .notion-table-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const serviceId = row.dataset.serviceId;
            if (!serviceId) return; // Skip empty state row

            let shouldShow = true;

            // Apply client filter
            if (clientFilter) {
                const clientCell = row.querySelector('.notion-badge');
                const clientText = clientCell?.textContent?.toLowerCase() || '';
                shouldShow = shouldShow && (clientText.includes(clientFilter.toLowerCase()) || clientFilter === 'general' && clientText.includes('general'));
            }

            // Apply status filter
            if (statusFilter) {
                const statusCell = row.querySelector('.notion-status');
                const statusText = statusCell?.textContent?.toLowerCase() || '';
                shouldShow = shouldShow && statusText.includes(statusFilter.toLowerCase());
            }

            // Apply type filter (would need service type data)
            if (typeFilter) {
                // This would need additional data in the service record
                // For now, just show all
            }

            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visibleCount++;
        });

        // Update empty state
        this.updateEmptyState(visibleCount);
    }

    updateEmptyState(visibleCount) {
        const tbody = document.getElementById('servicesTableBody');
        let emptyRow = tbody.querySelector('.notion-empty-state')?.closest('tr');

        if (visibleCount === 0 && !emptyRow) {
            // Create empty state for filtered results
            const newRow = document.createElement('tr');
            newRow.className = 'notion-table-row';
            newRow.innerHTML = `
                <td colspan="7" class="notion-table-cell">
                    <div class="notion-empty-state">
                        <i data-feather="search" class="notion-empty-icon" aria-hidden="true"></i>
                        <h4>No services found</h4>
                        <p>No services match your current filters. Try adjusting your search criteria.</p>
                        <button class="notion-btn notion-btn-secondary" onclick="this.closest('.notion-empty-state').dispatchEvent(new CustomEvent('clearFilters'))">
                            <i data-feather="x" class="notion-icon" aria-hidden="true"></i>
                            <span>Clear Filters</span>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(newRow);
            feather.replace(); // Re-initialize icons
        } else if (visibleCount > 0 && emptyRow) {
            emptyRow.remove();
        }
    }

    openAddServiceModal() {
        // This would open a modal or redirect to service creation
        console.log('Opening add service modal...');
        // You can integrate this with your existing modal system
    }

    refreshTable() {
        // Refresh the services table
        console.log('Refreshing services table...');
        window.location.reload();
    }

    initializeFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
}

// Global service functions for table actions
window.editService = function(serviceId) {
    console.log('Editing service:', serviceId);
    // Implement edit functionality
};

window.viewService = function(serviceId) {
    console.log('Viewing service:', serviceId);
    // Implement view functionality
};

window.deleteService = function(serviceId) {
    if (confirm('Are you sure you want to delete this service?')) {
        console.log('Deleting service:', serviceId);
        // Implement delete functionality
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ServicesManager();
});
</script>