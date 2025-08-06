<div class="modal-header">
    <h5 class="modal-title" id="serviceModalLabel">
        <?= isset($service) ? 'Edit Service' : 'Add Service' ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="serviceForm" action="<?= base_url('sales_orders_services/store') ?>" method="post">
        <?php if(isset($service)): ?>
            <input type="hidden" name="id" value="<?= $service['id'] ?>">
        <?php endif; ?>
        
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-select" id="client_id" name="client_id">
                <option value="">Select Client (Optional)</option>
                <?php if (isset($clients) && !empty($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>" 
                                <?= (isset($service) && !empty($service['client_id']) && $service['client_id'] == $client['id']) ? 'selected' : '' ?>>
                            <?= esc($client['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div id="client-count" class="form-text mt-1">
                <?php if (isset($clients)): ?>
                    <span class="text-success"><?= count($clients) ?> clients loaded</span>
                <?php else: ?>
                    <span class="text-warning">No clients loaded</span>
                <?php endif; ?>
            </div>
            <small class="text-muted">Leave empty for general services available to all clients</small>
        </div>
        
        <div class="mb-3">
            <label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="service_name" name="service_name" value="<?= isset($service) ? esc($service['service_name']) : '' ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="service_description" class="form-label">Service Description</label>
            <textarea class="form-control" id="service_description" name="service_description" rows="3"><?= isset($service) ? esc($service['service_description']) : '' ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="service_price" class="form-label">Service Price <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="service_price" name="service_price" step="0.01" value="<?= isset($service) ? $service['service_price'] : '' ?>" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"><?= isset($service) ? esc($service['notes']) : '' ?></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label d-block">Options</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-switch form-switch-md me-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= (isset($service) && $service['service_status'] == 'active') ? 'checked' : (!isset($service) ? 'checked' : '') ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input" type="checkbox" id="show_in_orders" name="show_in_orders" value="1" <?= (isset($service) && $service['show_in_orders'] == 1) ? 'checked' : (!isset($service) ? 'checked' : '') ?>>
                            <label class="form-check-label" for="show_in_orders">Show in Sales Orders</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
    <button type="submit" form="serviceForm" class="btn btn-primary">Save</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Service modal form loaded');
    
    // Replace feather icons if available
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script> 
