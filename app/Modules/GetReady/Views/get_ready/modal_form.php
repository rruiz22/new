<!-- Add Vehicle Modal Form -->
<form id="addVehicleForm" class="needs-validation" novalidate>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="vin_number" class="form-label">
                    <i data-feather="hash" class="icon-sm me-1"></i>
                    <?= lang('GetReady.vin_number') ?> <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-sm" id="vin_number" name="vin_number" 
                       placeholder="<?= lang('GetReady.vin_number') ?>" required maxlength="50">
                <div class="invalid-feedback">
                    <?= lang('GetReady.vin_required') ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="stock_number" class="form-label">
                    <i data-feather="tag" class="icon-sm me-1"></i>
                    <?= lang('GetReady.stock_number') ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="stock_number" name="stock_number" 
                       placeholder="<?= lang('GetReady.stock_number') ?>" maxlength="50">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label for="year" class="form-label">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('GetReady.year') ?>
                </label>
                <input type="number" class="form-control form-control-sm" id="year" name="year" 
                       placeholder="2024" min="1900" max="2030">
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="make" class="form-label">
                    <i data-feather="truck" class="icon-sm me-1"></i>
                    <?= lang('GetReady.make') ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="make" name="make" 
                       placeholder="BMW, Mercedes..." maxlength="50">
            </div>
        </div>

        <div class="col-md-5">
            <div class="mb-3">
                <label for="model" class="form-label">
                    <i data-feather="car" class="icon-sm me-1"></i>
                    <?= lang('GetReady.model') ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="model" name="model" 
                       placeholder="X5, E-Class..." maxlength="100">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="color" class="form-label">
                    <i data-feather="droplet" class="icon-sm me-1"></i>
                    <?= lang('GetReady.color') ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="color" name="color" 
                       placeholder="Black, White..." maxlength="50">
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="mileage" class="form-label">
                    <i data-feather="activity" class="icon-sm me-1"></i>
                    <?= lang('GetReady.mileage') ?>
                </label>
                <input type="number" class="form-control form-control-sm" id="mileage" name="mileage" 
                       placeholder="0" min="0">
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="priority" class="form-label">
                    <i data-feather="alert-circle" class="icon-sm me-1"></i>
                    <?= lang('GetReady.priority') ?>
                </label>
                <select class="form-select form-select-sm" id="priority" name="priority">
                    <option value="normal" selected><?= lang('GetReady.normal') ?></option>
                    <option value="high"><?= lang('GetReady.high') ?></option>
                    <option value="urgent"><?= lang('GetReady.urgent') ?></option>
                    <option value="low"><?= lang('GetReady.low') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="client_id" class="form-label">
                    <i data-feather="briefcase" class="icon-sm me-1"></i>
                    <?= lang('GetReady.client') ?> <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-sm" id="client_id" name="client_id" required>
                    <option value=""><?= lang('App.select_client') ?></option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>"><?= $client['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= lang('GetReady.client_required') ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="current_step_id" class="form-label">
                    <i data-feather="layers" class="icon-sm me-1"></i>
                    <?= lang('GetReady.step_required') ?>
                </label>
                <select class="form-select form-select-sm" id="current_step_id" name="current_step_id" required>
                    <?php foreach ($steps as $step): ?>
                        <option value="<?= $step['id'] ?>" <?= ($initial_step && $step['id'] == $initial_step['id']) ? 'selected' : '' ?>>
                            <?= $step['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?= lang('GetReady.step_required') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="current_location" class="form-label">
                    <i data-feather="map-pin" class="icon-sm me-1"></i>
                    <?= lang('GetReady.location') ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="current_location" name="current_location" 
                       placeholder="Lot A, Bay 5..." maxlength="255">
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="expected_completion" class="form-label">
                    <i data-feather="clock" class="icon-sm me-1"></i>
                    <?= lang('GetReady.expected_completion') ?>
                </label>
                <input type="datetime-local" class="form-control form-control-sm" id="expected_completion" name="expected_completion">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="notes" class="form-label">
                    <i data-feather="file-text" class="icon-sm me-1"></i>
                    <?= lang('GetReady.notes') ?>
                </label>
                <textarea class="form-control form-control-sm" id="notes" name="notes" rows="3" 
                          placeholder="<?= lang('GetReady.notes') ?>..."></textarea>
            </div>
        </div>
    </div>

    <!-- Hidden fields -->
    <input type="hidden" name="created_by" value="<?= auth()->id() ?>">
    <input type="hidden" name="status" value="active">
</form>

<style>
    /* Notion Compact Form Styling */
    .form-label {
        font-size: 12px;
        font-weight: 500;
        color: #37352f;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
    }

    .form-control-sm,
    .form-select-sm {
        font-size: 13px;
        padding: 6px 8px;
        border: 1px solid #e1e1e0;
        border-radius: 3px;
        background: #fff;
        transition: all 0.15s ease;
        min-height: 32px;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #2383e2;
        box-shadow: 0 0 0 1px #2383e2;
        outline: none;
    }

    .form-control-sm::placeholder {
        color: #9ca3af;
        font-size: 12px;
    }

    .invalid-feedback {
        font-size: 11px;
        color: #e03e3e;
    }

    .text-danger {
        color: #e03e3e !important;
    }

    .icon-sm {
        width: 14px;
        height: 14px;
        opacity: 0.6;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Form validation
    const form = document.getElementById('addVehicleForm');
    if (form) {
        // Bootstrap validation
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // VIN number validation (uppercase and alphanumeric)
        const vinInput = document.getElementById('vin_number');
        if (vinInput) {
            vinInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        }

        // Year validation
        const yearInput = document.getElementById('year');
        if (yearInput) {
            const currentYear = new Date().getFullYear();
            yearInput.max = currentYear + 1;
        }
    }
});
</script>