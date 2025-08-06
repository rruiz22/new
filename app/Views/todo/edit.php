<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.edit_todo') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.edit_todo') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.todos') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i data-feather="edit-3" class="text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="card-title mb-1"><?= lang('App.edit_todo') ?></h4>
                        <p class="text-muted mb-0"><?= lang('App.edit_todo_description') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('todos') ?>" class="btn btn-outline-secondary">
                                <i data-feather="arrow-left" class="icon-sm me-1"></i>
                                <?= lang('App.back_to_list') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Alerts -->
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                        <i data-feather="alert-triangle" class="icon-sm me-2 align-middle"></i>
                        <strong><?= lang('App.validation_errors') ?></strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= lang('App.close') ?>"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                        <i data-feather="x-circle" class="icon-sm me-2 align-middle"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= lang('App.close') ?>"></button>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= base_url('todos/update/' . $todo['id']) ?>" method="post" class="needs-validation" novalidate id="editTodoForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-lg-8">
                            <div class="card border shadow-none mb-4">
                                <div class="card-header bg-light-subtle">
                                    <h5 class="card-title mb-0">
                                        <i data-feather="info" class="icon-sm me-1"></i>
                                        <?= lang('App.todo_basic_information') ?>
                                    </h5>
                        </div>
                                <div class="card-body">
                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label fw-semibold">
                                            <?= lang('App.todo_title') ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?= old('title', $todo['title']) ?>" 
                                               placeholder="<?= lang('App.todo_title_placeholder') ?>" required>
                            <div class="invalid-feedback">
                                <?= lang('App.please_enter') ?> <?= lang('App.todo_title') ?>
                            </div>
                                        <div class="form-text"><?= lang('App.todo_title_help') ?></div>
                        </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-semibold">
                                            <?= lang('App.todo_description') ?>
                                        </label>
                                        <textarea class="form-control" id="description" name="description" rows="4" 
                                                  placeholder="<?= lang('App.todo_description_placeholder') ?>"><?= old('description', $todo['description']) ?></textarea>
                                        <div class="form-text"><?= lang('App.todo_description_help') ?></div>
                    </div>
                    
                                    <!-- Category and Tags Row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="category" class="form-label fw-semibold">
                                                    <i data-feather="folder" class="icon-sm me-1"></i>
                                                    <?= lang('App.todo_category') ?>
                                                </label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" id="category" name="category" 
                                                           value="<?= old('category', $todo['category']) ?>" 
                                                           placeholder="<?= lang('App.todo_category_placeholder') ?>"
                                                           list="categoryList">
                                                    <datalist id="categoryList">
                                                        <?php if (!empty($categories)): ?>
                                                            <?php foreach ($categories as $cat): ?>
                                                                <option value="<?= esc($cat) ?>">
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </datalist>
                                                </div>
                                                <div class="form-text"><?= lang('App.todo_category_help') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="tags" class="form-label fw-semibold">
                                                    <i data-feather="tag" class="icon-sm me-1"></i>
                                                    <?= lang('App.todo_tags') ?>
                                                </label>
                                                <input type="text" class="form-control" id="tags" name="tags" 
                                                       value="<?= old('tags', $todo['tags']) ?>" 
                                                       placeholder="<?= lang('App.todo_tags_placeholder') ?>">
                                                <div class="form-text"><?= lang('App.todo_tags_help') ?></div>
                        </div>
                        </div>
                    </div>
                    
                                    <!-- Time Estimates Row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="estimated_hours" class="form-label fw-semibold">
                                                    <i data-feather="clock" class="icon-sm me-1"></i>
                                                    <?= lang('App.todo_estimated_hours') ?>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" 
                                                           value="<?= old('estimated_hours', $todo['estimated_hours']) ?>" 
                                                           min="0" step="0.5" placeholder="0.0">
                                                    <span class="input-group-text"><?= lang('App.hours') ?></span>
                                                </div>
                                                <div class="form-text"><?= lang('App.todo_estimated_hours_help') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="actual_hours" class="form-label fw-semibold">
                                                    <i data-feather="check-circle" class="icon-sm me-1"></i>
                                                    <?= lang('App.todo_actual_hours') ?>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="actual_hours" name="actual_hours" 
                                                           value="<?= old('actual_hours', $todo['actual_hours']) ?>" 
                                                           min="0" step="0.5" placeholder="0.0">
                                                    <span class="input-group-text"><?= lang('App.hours') ?></span>
                        </div>
                                                <div class="form-text"><?= lang('App.todo_actual_hours_help') ?></div>
                                </div>
                            </div>
                        </div>

                                    <!-- Due Date -->
                                    <div class="mb-4">
                                        <label for="due_date" class="form-label fw-semibold">
                                            <i data-feather="calendar" class="icon-sm me-1"></i>
                                            <?= lang('App.todo_due_date') ?>
                                        </label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" 
                                               value="<?= old('due_date', $todo['due_date']) ?>">
                                        <div class="form-text"><?= lang('App.todo_due_date_help') ?></div>
                    </div>
                    
                                    <!-- Completion Notes (show if completed) -->
                                    <div class="mb-4" id="completionNotesSection" style="<?= $todo['status'] === 'completed' ? 'display: block;' : 'display: none;' ?>">
                                        <label for="completion_notes" class="form-label fw-semibold">
                                            <i data-feather="file-text" class="icon-sm me-1"></i>
                                            <?= lang('App.todo_completion_notes') ?>
                                        </label>
                                        <textarea class="form-control" id="completion_notes" name="completion_notes" rows="3" 
                                                  placeholder="<?= lang('App.todo_completion_notes_placeholder') ?>"><?= old('completion_notes', $todo['completion_notes']) ?></textarea>
                                        <div class="form-text"><?= lang('App.todo_completion_notes_help') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Status Card -->
                            <div class="card border shadow-none mb-4">
                                <div class="card-header bg-primary-subtle">
                                    <h5 class="card-title text-primary mb-0">
                                        <i data-feather="settings" class="icon-sm me-1"></i>
                                        <?= lang('App.todo_status_settings') ?>
                                    </h5>
                        </div>
                                <div class="card-body">
                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label for="todo_status" class="form-label fw-semibold">
                                            <?= lang('App.todo_status') ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="todo_status" name="status" required>
                                            <option value=""><?= lang('App.select_status') ?></option>
                                            <option value="pending" <?= old('status', $todo['status']) == 'pending' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_status_pending') ?>
                                            </option>
                                            <option value="in_progress" <?= old('status', $todo['status']) == 'in_progress' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_status_in_progress') ?>
                                            </option>
                                            <option value="completed" <?= old('status', $todo['status']) == 'completed' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_status_completed') ?>
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= lang('App.please_select') ?> <?= lang('App.todo_status') ?>
                        </div>
                    </div>
                    
                                    <!-- Priority -->
                                    <div class="mb-4">
                                        <label for="priority" class="form-label fw-semibold">
                                            <?= lang('App.todo_priority') ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="priority" name="priority" required>
                                            <option value="low" <?= old('priority', $todo['priority']) == 'low' ? 'selected' : '' ?>>
                                                <span class="badge bg-secondary"><?= lang('App.todo_priority_low') ?></span>
                                            </option>
                                            <option value="medium" <?= old('priority', $todo['priority']) == 'medium' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_medium') ?>
                                            </option>
                                            <option value="high" <?= old('priority', $todo['priority']) == 'high' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_high') ?>
                                            </option>
                                            <option value="urgent" <?= old('priority', $todo['priority']) == 'urgent' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_urgent') ?>
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= lang('App.please_select') ?> <?= lang('App.todo_priority') ?>
                        </div>
                    </div>
                    
                                    <!-- Current Status Display -->
                                    <div class="alert alert-info border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i data-feather="info" class="icon-sm"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="alert-heading mb-1"><?= lang('App.current_status') ?></h6>
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-<?= $todo['status'] === 'completed' ? 'success' : ($todo['status'] === 'in_progress' ? 'info' : 'warning') ?>">
                                                        <?= lang('App.todo_status_' . $todo['status']) ?>
                                                    </span>
                                                    <span class="badge bg-<?= $todo['priority'] === 'urgent' ? 'danger' : ($todo['priority'] === 'high' ? 'warning' : ($todo['priority'] === 'medium' ? 'info' : 'secondary')) ?>">
                                                        <?= lang('App.todo_priority_' . $todo['priority']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                        </div>
                        </div>
                    </div>
                    
                            <!-- Todo Information Card -->
                            <div class="card border shadow-none mb-4">
                                <div class="card-header bg-success-subtle">
                                    <h5 class="card-title text-success mb-0">
                                        <i data-feather="info" class="icon-sm me-1"></i>
                                        <?= lang('App.todo_information') ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.created_at') ?>:</td>
                                                    <td class="text-muted"><?= date('M j, Y H:i', strtotime($todo['created_at'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.updated_at') ?>:</td>
                                                    <td class="text-muted"><?= date('M j, Y H:i', strtotime($todo['updated_at'])) ?></td>
                                                </tr>
                                                <?php if (!empty($todo['formatted_due_date'])): ?>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.due_date') ?>:</td>
                                                    <td class="text-muted"><?= $todo['formatted_due_date'] ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($todo['estimated_hours'])): ?>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.estimated') ?>:</td>
                                                    <td class="text-muted"><?= $todo['estimated_hours'] ?> <?= lang('App.hours') ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($todo['actual_hours'])): ?>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.actual') ?>:</td>
                                                    <td class="text-muted"><?= $todo['actual_hours'] ?> <?= lang('App.hours') ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                        </div>
                        </div>
                    </div>
                    
                            <!-- Actions Card -->
                            <div class="card border shadow-none">
                                <div class="card-header bg-warning-subtle">
                                    <h5 class="card-title text-warning mb-0">
                                        <i data-feather="zap" class="icon-sm me-1"></i>
                                        <?= lang('App.quick_actions') ?>
                                    </h5>
                        </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <?php if ($todo['status'] !== 'completed'): ?>
                                            <button type="button" class="btn btn-success btn-sm" id="markCompleted">
                                                <i data-feather="check" class="icon-sm me-1"></i>
                                                <?= lang('App.mark_as_completed') ?>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-warning btn-sm" id="reopenTodo">
                                                <i data-feather="rotate-ccw" class="icon-sm me-1"></i>
                                                <?= lang('App.reopen_todo') ?>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-info btn-sm" id="duplicateTodo">
                                            <i data-feather="copy" class="icon-sm me-1"></i>
                                            <?= lang('App.duplicate_todo') ?>
                                        </button>
                                        
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteTodo">
                                            <i data-feather="trash-2" class="icon-sm me-1"></i>
                                            <?= lang('App.delete_todo') ?>
                                        </button>
                        </div>
                    </div>
                        </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card border-0 shadow-none bg-light-subtle">
                                <div class="card-body">
                            <div class="hstack gap-2 justify-content-end">
                                        <a href="<?= base_url('todos') ?>" class="btn btn-light">
                                            <i data-feather="x" class="icon-sm me-1"></i>
                                            <?= lang('App.cancel') ?>
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="updateBtn">
                                            <i data-feather="save" class="icon-sm me-1"></i>
                                            <?= lang('App.update_todo') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    console.log('🎯 TODO Edit - Modern Velzon Theme Loaded');
    
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Form validation
    const form = document.getElementById('editTodoForm');
    
    // Bootstrap validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
    
    // Status change handler
    $('#todo_status').on('change', function() {
        const status = $(this).val();
        const completionNotesSection = $('#completionNotesSection');
        
        if (status === 'completed') {
            completionNotesSection.slideDown(300);
        } else {
            completionNotesSection.slideUp(300);
        }
        
        // Update visual indicators
        updateStatusIndicators(status);
    });
    
    // Priority change handler
    $('#priority').on('change', function() {
        const priority = $(this).val();
        updatePriorityIndicators(priority);
    });
    
    // Quick action buttons
    $('#markCompleted').on('click', function() {
        $('#todo_status').val('completed').trigger('change');
        showToast('info', '<?= lang('App.status_updated_to_completed') ?>');
    });
    
    $('#reopenTodo').on('click', function() {
        $('#todo_status').val('pending').trigger('change');
        showToast('info', '<?= lang('App.status_updated_to_pending') ?>');
    });
    
    $('#duplicateTodo').on('click', function() {
        Swal.fire({
            title: '<?= lang('App.duplicate_todo_title') ?>',
            text: '<?= lang('App.duplicate_todo_text') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_duplicate') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create new todo based on current data
                window.open('<?= base_url('todos/create') ?>?duplicate=<?= $todo['id'] ?>', '_blank');
            }
        });
    });
    
    $('#deleteTodo').on('click', function() {
        Swal.fire({
            title: '<?= lang('App.confirm_delete_todo') ?>',
            text: '<?= lang('App.delete_todo_confirmation') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete todo via AJAX
                deleteTodoAjax(<?= $todo['id'] ?>);
            }
        });
    });
    
    // Update status indicators
    function updateStatusIndicators(status) {
        const badge = $('.alert-info .badge:first');
        const statusColors = {
            'pending': 'warning',
            'in_progress': 'info',
            'completed': 'success'
        };
        
        badge.removeClass('bg-warning bg-info bg-success')
             .addClass('bg-' + statusColors[status])
             .text('<?= lang('App.todo_status_') ?>' + status);
    }
    
    // Update priority indicators
    function updatePriorityIndicators(priority) {
        const badge = $('.alert-info .badge:last');
        const priorityColors = {
            'low': 'secondary',
            'medium': 'info',
            'high': 'warning',
            'urgent': 'danger'
        };
        
        badge.removeClass('bg-secondary bg-info bg-warning bg-danger')
             .addClass('bg-' + priorityColors[priority])
             .text('<?= lang('App.todo_priority_') ?>' + priority);
    }
    
    // Delete todo via AJAX
    function deleteTodoAjax(todoId) {
        $.ajax({
            url: '<?= base_url('todos/delete') ?>/' + todoId,
            type: 'DELETE',
            dataType: 'json',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '<?= base_url('todos') ?>';
                    }, 1500);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', '<?= lang('App.error_deleting_todo') ?>');
            }
        });
    }
    
    // Toast notification function
    function showToast(type, message) {
        const toastId = 'toast-' + Date.now();
        const toastClass = type === 'success' ? 'text-bg-success' : 
                          type === 'error' ? 'text-bg-danger' : 
                          type === 'warning' ? 'text-bg-warning' : 'text-bg-info';
        
        const toastHtml = `
            <div id="${toastId}" class="toast ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i data-feather="${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : type === 'warning' ? 'alert-triangle' : 'info'}" class="icon-sm me-2"></i>
                    <strong class="me-auto"><?= lang('App.notification') ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="<?= lang('App.close') ?>"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        // Add toast container if it doesn't exist
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
        }
        
        $('#toast-container').append(toastHtml);
        
        // Initialize and show toast
        const toastElement = $('#' + toastId);
        const toast = new bootstrap.Toast(toastElement[0], {
            autohide: true,
            delay: 5000
        });
        
        toast.show();
        
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Remove toast element after it's hidden
        toastElement.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
    
    // Auto-save draft functionality (optional)
    let autoSaveTimeout;
    $('input, textarea, select').on('input change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic here if needed
            console.log('Auto-saving draft...');
        }, 5000);
    });
    
    // Character counter for title and description
    $('#title').on('input', function() {
        const maxLength = 255;
        const currentLength = $(this).val().length;
        const remaining = maxLength - currentLength;
        
        let counterText = `${currentLength}/${maxLength}`;
        if (remaining < 20) {
            counterText = `<span class="text-warning">${counterText}</span>`;
        }
        if (remaining < 0) {
            counterText = `<span class="text-danger">${counterText}</span>`;
        }
        
        // Add or update character counter
        let counter = $('#title').next('.char-counter');
        if (counter.length === 0) {
            $('#title').after(`<div class="char-counter small text-muted mt-1">${counterText}</div>`);
        } else {
            counter.html(counterText);
        }
    });
    
    // Trigger initial character count
    $('#title').trigger('input');
    
    // Form submission with loading state
    $('#updateBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true)
           .html('<i class="spinner-border spinner-border-sm me-1"></i> <?= lang('App.updating') ?>...');
        
        // Re-enable button after 3 seconds (in case form doesn't submit)
        setTimeout(() => {
            btn.prop('disabled', false).html(originalText);
        }, 3000);
    });
});
</script>

<!-- Load SweetAlert2 if not already loaded -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<?= $this->endSection() ?>
