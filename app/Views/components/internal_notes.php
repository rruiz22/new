<?php
/**
 * Internal Notes Component
 * Reutilizable para todos los módulos (Sales Orders, Service Orders, CarWash, Recon)
 * 
 * Parámetros requeridos:
 * - $order_id: ID de la orden
 * - $module_type: Tipo de módulo ('sales_orders', 'service_orders', 'car_wash_orders', 'recon_orders')
 * 
 * Parámetros opcionales:
 * - $show_mentions_tab: Mostrar tab de menciones (default: true)
 * - $show_team_activity_tab: Mostrar tab de actividad del team (default: true)
 * - $max_char_count: Máximo de caracteres (default: 5000)
 */

$order_id = $order_id ?? 0;
$module_type = $module_type ?? 'sales_orders';
$show_mentions_tab = $show_mentions_tab ?? true;
$show_team_activity_tab = $show_team_activity_tab ?? true;
$max_char_count = $max_char_count ?? 5000;

// Generar IDs únicos para el componente
$component_id = 'internal_notes_' . $module_type . '_' . $order_id;
$notes_form_id = $component_id . '_form';
$notes_content_id = $component_id . '_content';
$notes_list_id = $component_id . '_list';
$mentions_list_id = $component_id . '_mentions';
$team_activity_list_id = $component_id . '_team_activity';
?>

<!-- Internal Notes Component -->
<div class="card internal-notes-component" id="<?= $component_id ?>">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="<?= $component_id ?>_tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="<?= $component_id ?>_notes_tab" data-bs-toggle="tab" 
                        data-bs-target="#<?= $component_id ?>_notes_pane" type="button" role="tab" 
                        aria-controls="<?= $component_id ?>_notes_pane" aria-selected="true">
                    <i data-feather="file-text" class="icon-xs me-1"></i>
                    Internal Notes
                    <span class="badge bg-secondary ms-1" id="<?= $component_id ?>_notes_count">0</span>
                </button>
            </li>
            <?php if ($show_mentions_tab): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="<?= $component_id ?>_mentions_tab" data-bs-toggle="tab" 
                        data-bs-target="#<?= $component_id ?>_mentions_pane" type="button" role="tab" 
                        aria-controls="<?= $component_id ?>_mentions_pane" aria-selected="false">
                    <i data-feather="at-sign" class="icon-xs me-1"></i>
                    My Mentions
                    <span class="badge bg-warning ms-1" id="<?= $component_id ?>_mentions_count">0</span>
                </button>
            </li>
            <?php endif; ?>
            <?php if ($show_team_activity_tab): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="<?= $component_id ?>_team_tab" data-bs-toggle="tab" 
                        data-bs-target="#<?= $component_id ?>_team_pane" type="button" role="tab" 
                        aria-controls="<?= $component_id ?>_team_pane" aria-selected="false">
                    <i data-feather="users" class="icon-xs me-1"></i>
                    Team Activity
                    <span class="badge bg-info ms-1" id="<?= $component_id ?>_team_count">0</span>
                </button>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card-body">
        <!-- Tab Content -->
        <div class="tab-content" id="<?= $component_id ?>_tabs_content">
            <!-- Internal Notes Tab -->
            <div class="tab-pane fade show active" id="<?= $component_id ?>_notes_pane" role="tabpanel" 
                 aria-labelledby="<?= $component_id ?>_notes_tab">
                <div class="p-3">
                    <!-- Add Note Form -->
                    <form id="<?= $notes_form_id ?>" class="mb-3">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <input type="hidden" name="module_type" value="<?= $module_type ?>">
                        
                        <div class="mb-2">
                            <div class="position-relative">
                                <textarea class="form-control" id="<?= $notes_content_id ?>" name="note_content" 
                                         rows="3" placeholder="Add an internal note... Use @username to mention staff members" 
                                         required maxlength="<?= $max_char_count ?>"></textarea>
                                <div id="<?= $component_id ?>_mention_suggestions" class="mention-suggestions-dropdown" style="display: none;"></div>
                            </div>
                            <div class="form-text d-flex justify-content-between">
                                <div>
                                    <i data-feather="info" class="icon-xs me-1"></i>
                                    Type @ followed by username to mention staff members. Supports file attachments.
                                </div>
                                <div>
                                    <span id="<?= $component_id ?>_char_count" class="text-muted">0</span>/<span class="text-muted"><?= $max_char_count ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="file" id="<?= $component_id ?>_attachments" name="attachments[]" 
                                       multiple class="form-control form-control-sm d-none" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                        onclick="document.getElementById('<?= $component_id ?>_attachments').click()">
                                    <i data-feather="paperclip" class="icon-xs me-1"></i>
                                    Attach Files
                                </button>
                                <span id="<?= $component_id ?>_attachment_count" class="text-muted small ms-2"></span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i data-feather="send" class="icon-xs me-1"></i>
                                Add Note
                            </button>
                        </div>
                    </form>
                    
                    <!-- Notes Filter -->
                    <div class="notes-filter-bar mb-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i data-feather="search" class="icon-xs"></i></span>
                                    <input type="text" class="form-control" id="<?= $component_id ?>_search" placeholder="Search notes...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select form-select-sm" id="<?= $component_id ?>_author_filter">
                                    <option value="">All Authors</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select form-select-sm" id="<?= $component_id ?>_date_filter">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes List -->
                    <div id="<?= $notes_list_id ?>">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading internal notes...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($show_mentions_tab): ?>
            <!-- My Mentions Tab -->
            <div class="tab-pane fade" id="<?= $component_id ?>_mentions_pane" role="tabpanel" 
                 aria-labelledby="<?= $component_id ?>_mentions_tab">
                <div class="p-3">
                    <div id="<?= $mentions_list_id ?>">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading mentions...</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($show_team_activity_tab): ?>
            <!-- Team Activity Tab -->
            <div class="tab-pane fade" id="<?= $component_id ?>_team_pane" role="tabpanel" 
                 aria-labelledby="<?= $component_id ?>_team_tab">
                <div class="p-3">
                    <div id="<?= $team_activity_list_id ?>">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading team activity...</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Internal Notes Component JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const componentId = '<?= $component_id ?>';
    const orderId = <?= $order_id ?>;
    const moduleType = '<?= $module_type ?>';
    
    // Initialize Internal Notes Component
    initInternalNotesComponent(componentId, orderId, moduleType);
});

function initInternalNotesComponent(componentId, orderId, moduleType) {
    const form = document.getElementById(componentId + '_form');
    const contentTextarea = document.getElementById(componentId + '_content');
    const charCountSpan = document.getElementById(componentId + '_char_count');
    const attachmentInput = document.getElementById(componentId + '_attachments');
    const attachmentCountSpan = document.getElementById(componentId + '_attachment_count');
    
    // Character count
    contentTextarea.addEventListener('input', function() {
        charCountSpan.textContent = this.value.length;
    });
    
    // Attachment count
    attachmentInput.addEventListener('change', function() {
        const fileCount = this.files.length;
        attachmentCountSpan.textContent = fileCount > 0 ? `${fileCount} file(s) selected` : '';
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitInternalNote(componentId, orderId, moduleType);
    });
    
    // Load initial data
    loadInternalNotes(componentId, orderId, moduleType);
    <?php if ($show_mentions_tab): ?>
    loadMyMentions(componentId, orderId, moduleType);
    <?php endif; ?>
    <?php if ($show_team_activity_tab): ?>
    loadTeamActivity(componentId, orderId, moduleType);
    <?php endif; ?>
}

// Function placeholders - estos deben implementarse en el JavaScript global
function submitInternalNote(componentId, orderId, moduleType) {
    console.log('Submit internal note:', {componentId, orderId, moduleType});
    // Implementar lógica de envío
}

function loadInternalNotes(componentId, orderId, moduleType) {
    console.log('Load internal notes:', {componentId, orderId, moduleType});
    // Implementar carga de notas
}

function loadMyMentions(componentId, orderId, moduleType) {
    console.log('Load mentions:', {componentId, orderId, moduleType});
    // Implementar carga de menciones
}

function loadTeamActivity(componentId, orderId, moduleType) {
    console.log('Load team activity:', {componentId, orderId, moduleType});
    // Implementar carga de actividad del team
}
</script>