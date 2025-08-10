<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Email & SMS Templates<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Email & SMS Templates<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Settings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Template Management</h4>
                <p class="text-muted mb-0">Manage email and SMS templates for all modules</p>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#email-templates" role="tab">
                            <i class="ri-mail-line me-1"></i>
                            Email Templates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#sms-templates" role="tab">
                            <i class="ri-message-2-line me-1"></i>
                            SMS Templates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#variables" role="tab">
                            <i class="ri-code-line me-1"></i>
                            Available Variables
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content pt-4">
                    <!-- Email Templates Tab -->
                    <div class="tab-pane active" id="email-templates" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Email Templates</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addEmailTemplate()">
                                <i class="ri-add-line me-1"></i> Add Template
                            </button>
                        </div>

                        <div id="email-templates-container">
                            <?php if (!empty($emailTemplates)): ?>
                                <?php foreach ($emailTemplates as $index => $template): ?>
                                    <div class="email-template-item border rounded p-3 mb-3" data-index="<?= $index ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Template Name</label>
                                                <input type="text" class="form-control" name="email_templates[<?= $index ?>][name]" value="<?= esc($template['name']) ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Module</label>
                                                <select class="form-select" name="email_templates[<?= $index ?>][module]">
                                                    <option value="general" <?= ($template['module'] ?? 'general') === 'general' ? 'selected' : '' ?>>General</option>
                                                    <option value="sales_orders" <?= ($template['module'] ?? '') === 'sales_orders' ? 'selected' : '' ?>>Sales Orders</option>
                                                    <option value="service_orders" <?= ($template['module'] ?? '') === 'service_orders' ? 'selected' : '' ?>>Service Orders</option>
                                                    <option value="car_wash" <?= ($template['module'] ?? '') === 'car_wash' ? 'selected' : '' ?>>Car Wash</option>
                                                    <option value="recon_orders" <?= ($template['module'] ?? '') === 'recon_orders' ? 'selected' : '' ?>>Recon Orders</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Subject</label>
                                                <input type="text" class="form-control" name="email_templates[<?= $index ?>][subject]" value="<?= esc($template['subject']) ?>" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeEmailTemplate(<?= $index ?>)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <label class="form-label">Content</label>
                                                <textarea class="form-control" name="email_templates[<?= $index ?>][content]" rows="8" required><?= esc($template['content']) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="ri-mail-line display-4 text-muted"></i>
                                    <p class="text-muted">No email templates found. Click "Add Template" to create your first template.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-success" onclick="saveEmailTemplates()">
                                <i class="ri-save-line me-1"></i> Save Email Templates
                            </button>
                        </div>
                    </div>

                    <!-- SMS Templates Tab -->
                    <div class="tab-pane" id="sms-templates" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">SMS Templates</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addSmsTemplate()">
                                <i class="ri-add-line me-1"></i> Add Template
                            </button>
                        </div>

                        <div id="sms-templates-container">
                            <?php if (!empty($smsTemplates)): ?>
                                <?php foreach ($smsTemplates as $index => $template): ?>
                                    <div class="sms-template-item border rounded p-3 mb-3" data-index="<?= $index ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Template Name</label>
                                                <input type="text" class="form-control" name="sms_templates[<?= $index ?>][name]" value="<?= esc($template['name']) ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Module</label>
                                                <select class="form-select" name="sms_templates[<?= $index ?>][module]">
                                                    <option value="general" <?= ($template['module'] ?? 'general') === 'general' ? 'selected' : '' ?>>General</option>
                                                    <option value="sales_orders" <?= ($template['module'] ?? '') === 'sales_orders' ? 'selected' : '' ?>>Sales Orders</option>
                                                    <option value="service_orders" <?= ($template['module'] ?? '') === 'service_orders' ? 'selected' : '' ?>>Service Orders</option>
                                                    <option value="car_wash" <?= ($template['module'] ?? '') === 'car_wash' ? 'selected' : '' ?>>Car Wash</option>
                                                    <option value="recon_orders" <?= ($template['module'] ?? '') === 'recon_orders' ? 'selected' : '' ?>>Recon Orders</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Character Count</label>
                                                <div class="input-group">
                                                    <span class="input-group-text character-count">0</span>
                                                    <span class="input-group-text">/160</span>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeSmsTemplate(<?= $index ?>)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <label class="form-label">Content</label>
                                                <textarea class="form-control sms-content" name="sms_templates[<?= $index ?>][content]" rows="4" maxlength="1600" required><?= esc($template['content']) ?></textarea>
                                                <div class="form-text">Maximum 1600 characters. SMS messages over 160 characters will be split into multiple messages.</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="ri-message-2-line display-4 text-muted"></i>
                                    <p class="text-muted">No SMS templates found. Click "Add Template" to create your first template.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-success" onclick="saveSmsTemplates()">
                                <i class="ri-save-line me-1"></i> Save SMS Templates
                            </button>
                        </div>
                    </div>

                    <!-- Variables Tab -->
                    <div class="tab-pane" id="variables" role="tabpanel">
                        <h5 class="mb-3">Available Variables by Module</h5>
                        <div class="row">
                            <?php foreach ($availableVariables as $module => $variables): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0"><?= ucwords(str_replace('_', ' ', $module)) ?></h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="variable-list">
                                                <?php foreach ($variables as $variable): ?>
                                                    <span class="badge bg-primary-subtle text-primary me-1 mb-1 variable-badge" onclick="copyToClipboard('<?= $variable ?>')" title="Click to copy">
                                                        <?= $variable ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>How to use:</strong> Click on any variable badge to copy it to your clipboard, then paste it into your email or SMS templates. Variables will be automatically replaced with actual values when templates are used.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.variable-badge {
    cursor: pointer;
    transition: all 0.2s ease;
}

.variable-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.template-item {
    transition: all 0.2s ease;
}

.template-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.character-count {
    min-width: 60px;
    text-align: center;
}

.sms-content {
    resize: vertical;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let emailTemplateIndex = <?= count($emailTemplates) ?>;
let smsTemplateIndex = <?= count($smsTemplates) ?>;

// Add Email Template
function addEmailTemplate() {
    const container = document.getElementById('email-templates-container');
    
    // Remove empty message if exists
    const emptyMessage = container.querySelector('.text-center');
    if (emptyMessage) {
        emptyMessage.remove();
    }
    
    const templateHtml = `
        <div class="email-template-item border rounded p-3 mb-3" data-index="${emailTemplateIndex}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" class="form-control" name="email_templates[${emailTemplateIndex}][name]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Module</label>
                    <select class="form-select" name="email_templates[${emailTemplateIndex}][module]">
                        <option value="general">General</option>
                        <option value="sales_orders">Sales Orders</option>
                        <option value="service_orders">Service Orders</option>
                        <option value="car_wash">Car Wash</option>
                        <option value="recon_orders">Recon Orders</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-control" name="email_templates[${emailTemplateIndex}][subject]" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeEmailTemplate(${emailTemplateIndex})">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea class="form-control" name="email_templates[${emailTemplateIndex}][content]" rows="8" required></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', templateHtml);
    emailTemplateIndex++;
}

// Add SMS Template
function addSmsTemplate() {
    const container = document.getElementById('sms-templates-container');
    
    // Remove empty message if exists
    const emptyMessage = container.querySelector('.text-center');
    if (emptyMessage) {
        emptyMessage.remove();
    }
    
    const templateHtml = `
        <div class="sms-template-item border rounded p-3 mb-3" data-index="${smsTemplateIndex}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Template Name</label>
                    <input type="text" class="form-control" name="sms_templates[${smsTemplateIndex}][name]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Module</label>
                    <select class="form-select" name="sms_templates[${smsTemplateIndex}][module]">
                        <option value="general">General</option>
                        <option value="sales_orders">Sales Orders</option>
                        <option value="service_orders">Service Orders</option>
                        <option value="car_wash">Car Wash</option>
                        <option value="recon_orders">Recon Orders</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Character Count</label>
                    <div class="input-group">
                        <span class="input-group-text character-count">0</span>
                        <span class="input-group-text">/160</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeSmsTemplate(${smsTemplateIndex})">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea class="form-control sms-content" name="sms_templates[${smsTemplateIndex}][content]" rows="4" maxlength="1600" required></textarea>
                    <div class="form-text">Maximum 1600 characters. SMS messages over 160 characters will be split into multiple messages.</div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', templateHtml);
    smsTemplateIndex++;
    
    // Add character counter to new textarea
    updateCharacterCounters();
}

// Remove Email Template
function removeEmailTemplate(index) {
    const template = document.querySelector(`[data-index="${index}"].email-template-item`);
    if (template) {
        template.remove();
        
        // Show empty message if no templates left
        const container = document.getElementById('email-templates-container');
        if (container.children.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="ri-mail-line display-4 text-muted"></i>
                    <p class="text-muted">No email templates found. Click "Add Template" to create your first template.</p>
                </div>
            `;
        }
    }
}

// Remove SMS Template
function removeSmsTemplate(index) {
    const template = document.querySelector(`[data-index="${index}"].sms-template-item`);
    if (template) {
        template.remove();
        
        // Show empty message if no templates left
        const container = document.getElementById('sms-templates-container');
        if (container.children.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="ri-message-2-line display-4 text-muted"></i>
                    <p class="text-muted">No SMS templates found. Click "Add Template" to create your first template.</p>
                </div>
            `;
        }
    }
}

// Save Email Templates
function saveEmailTemplates() {
    const formData = new FormData();
    
    // Collect all email template data
    const emailTemplates = [];
    document.querySelectorAll('.email-template-item').forEach((item, index) => {
        const name = item.querySelector('input[name*="[name]"]').value;
        const module = item.querySelector('select[name*="[module]"]').value;
        const subject = item.querySelector('input[name*="[subject]"]').value;
        const content = item.querySelector('textarea[name*="[content]"]').value;
        
        if (name && subject && content) {
            emailTemplates.push({
                name: name,
                module: module,
                subject: subject,
                content: content
            });
        }
    });
    
    formData.append('email_templates', JSON.stringify(emailTemplates));
    
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-2-line me-1 spinner-border spinner-border-sm"></i> Saving...';
    btn.disabled = true;
    
    fetch('<?= base_url('settings/saveEmailTemplates') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            showToast('success', data.message);
        } else {
            showToast('error', data.message || 'Error saving templates');
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        showToast('error', 'Error saving templates');
        console.error('Error:', error);
    });
}

// Save SMS Templates
function saveSmsTemplates() {
    const formData = new FormData();
    
    // Collect all SMS template data
    const smsTemplates = [];
    document.querySelectorAll('.sms-template-item').forEach((item, index) => {
        const name = item.querySelector('input[name*="[name]"]').value;
        const module = item.querySelector('select[name*="[module]"]').value;
        const content = item.querySelector('textarea[name*="[content]"]').value;
        
        if (name && content) {
            smsTemplates.push({
                name: name,
                module: module,
                content: content
            });
        }
    });
    
    formData.append('sms_templates', JSON.stringify(smsTemplates));
    
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-2-line me-1 spinner-border spinner-border-sm"></i> Saving...';
    btn.disabled = true;
    
    fetch('<?= base_url('settings/saveSmsTemplates') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            showToast('success', data.message);
        } else {
            showToast('error', data.message || 'Error saving templates');
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        showToast('error', 'Error saving templates');
        console.error('Error:', error);
    });
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('success', `Copied "${text}" to clipboard`);
    }).catch(err => {
        console.error('Error copying to clipboard:', err);
        showToast('error', 'Failed to copy to clipboard');
    });
}

// Update character counters
function updateCharacterCounters() {
    document.querySelectorAll('.sms-content').forEach(textarea => {
        const counter = textarea.closest('.sms-template-item').querySelector('.character-count');
        
        function updateCount() {
            counter.textContent = textarea.value.length;
            
            // Color coding
            if (textarea.value.length > 160) {
                counter.classList.add('text-warning');
            } else {
                counter.classList.remove('text-warning');
            }
            
            if (textarea.value.length > 1600) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-warning');
            } else {
                counter.classList.remove('text-danger');
            }
        }
        
        // Update on input
        textarea.addEventListener('input', updateCount);
        
        // Initial update
        updateCount();
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
            style: { background: colors[type] || colors.info },
        }).showToast();
    }
}

// Initialize character counters on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharacterCounters();
});
</script>
<?= $this->endSection() ?>
