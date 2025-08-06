<div class="modal-header">
    <h5 class="modal-title" id="serviceModalLabel">
        <?php if(isset($service)): ?>
            <i data-feather="edit-2" class="icon-sm me-2"></i>
            <?= lang('App.edit') ?> Service: <?= esc($service['service_name']) ?>
        <?php else: ?>
            <i data-feather="plus" class="icon-sm me-2"></i>
            <?= lang('App.add') ?> New Service
        <?php endif; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form id="serviceForm" action="<?= base_url('service_orders_services/store') ?>" method="post">
        <?php if(isset($service)): ?>
            <input type="hidden" name="id" value="<?= $service['id'] ?>">
        <?php endif; ?>
        
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-select" id="client_id" name="client_id" data-selected-id="<?= isset($service) ? $service['client_id'] : '' ?>">
                <option value="">Select Client (Optional)</option>
                <!-- Los clientes se cargarán dinámicamente -->
            </select>
            <div id="client-count" class="form-text mt-1"></div>
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
                            <label class="form-check-label" for="show_in_orders">Show in Service Orders</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i data-feather="x" class="icon-sm me-1"></i>
        <?= lang('App.close') ?>
    </button>
    <button type="submit" form="serviceForm" class="btn btn-primary">
        <?php if(isset($service)): ?>
            <i data-feather="save" class="icon-sm me-1"></i>
            <?= lang('App.update') ?>
        <?php else: ?>
            <i data-feather="plus" class="icon-sm me-1"></i>
            <?= lang('App.create') ?>
        <?php endif; ?>
    </button>
</div>

<script>
$(document).ready(function() {
    // Initialize Feather icons when modal is loaded
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // NO registrar manejador de eventos aquí para evitar duplicados
    // El manejador se registra en index.php usando event delegation
    
    // Solo cargar los clientes cuando el modal se carga
    loadClients();
});

// Función simple para cargar clientes
function loadClients() {
    const clientSelect = document.getElementById('client_id');
    const clientCount = document.getElementById('client-count');
    
    if (!clientSelect) {
        console.error('client_id select not found');
        return;
    }
    
    if (!clientCount) {
        console.error('client-count div not found');
        return;
    }
    
    // Obtener el valor actual seleccionado (para modo edición)
    const currentClientId = clientSelect.getAttribute('data-selected-id') || '';
    
    // Mostrar estado de carga
    clientCount.innerHTML = '<span class="text-info">Loading clients...</span>';
    
    // URL del endpoint
    const url = '<?= base_url('clients/get_clients_json') ?>';
    
    // Hacer la petición
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (!Array.isArray(data)) {
                throw new Error('Response is not an array');
            }
            
            // Limpiar select (mantener primera opción)
            clientSelect.innerHTML = '<option value="">Select Client (Optional)</option>';
            
            if (data.length === 0) {
                clientCount.innerHTML = '<span class="text-warning">No active clients found</span>';
                return;
            }
            
            // Agregar cada cliente
            data.forEach((client, index) => {
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = client.name;
                
                // Si estamos editando un servicio, seleccionar el cliente correspondiente
                if (currentClientId && client.id == currentClientId) {
                    option.selected = true;
                }
                
                clientSelect.appendChild(option);
            });
            
            // Mostrar contador
            clientCount.innerHTML = `<span class="text-success">${data.length} clients loaded</span>`;
        })
        .catch(error => {
            console.error('Error loading clients:', error);
            clientCount.innerHTML = '<span class="text-danger">Error loading clients</span>';
            
            // Agregar opción de error
            const errorOption = document.createElement('option');
            errorOption.disabled = true;
            errorOption.textContent = 'Error loading clients';
            clientSelect.appendChild(errorOption);
        });
}

// Ejecutar cuando el script se carga
loadClients();
</script> 