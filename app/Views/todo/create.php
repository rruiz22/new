<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.create_todo') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.create_todo') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.todos') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i data-feather="plus-circle" class="text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="card-title mb-1"><?= lang('App.create_todo') ?></h4>
                        <p class="text-muted mb-0">Create a new task to organize your work effectively</p>
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
                <form action="<?= base_url('todos/store') ?>" method="post" class="needs-validation" novalidate id="createTodoForm">
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
                                               value="<?= old('title') ?>" 
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
                                                  placeholder="<?= lang('App.todo_description_placeholder') ?>"><?= old('description') ?></textarea>
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
                                                           value="<?= old('category') ?>" 
                                                           placeholder="<?= lang('App.todo_category_placeholder') ?>"
                                                           list="categoryList">
                                                    <datalist id="categoryList">
                                                        <option value="<?= lang('App.todo_category_work') ?>">
                                                        <option value="<?= lang('App.todo_category_personal') ?>">
                                                        <option value="<?= lang('App.todo_category_urgent') ?>">
                                                        <option value="<?= lang('App.todo_category_project') ?>">
                                                        <option value="<?= lang('App.todo_category_meeting') ?>">
                                                        <option value="<?= lang('App.todo_category_other') ?>">
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
                                                       value="<?= old('tags') ?>" 
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
                                                           value="<?= old('estimated_hours') ?>" 
                                                           min="0" step="0.5" placeholder="0.0">
                                                    <span class="input-group-text"><?= lang('App.hours') ?></span>
                                                </div>
                                                <div class="form-text"><?= lang('App.todo_estimated_hours_help') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Due Date -->
                                            <div class="mb-4">
                                                <label for="due_date" class="form-label fw-semibold">
                                                    <i data-feather="calendar" class="icon-sm me-1"></i>
                                                    <?= lang('App.todo_due_date') ?>
                                                </label>
                                                <input type="date" class="form-control" id="due_date" name="due_date" 
                                                       value="<?= old('due_date') ?>">
                                                <div class="form-text"><?= lang('App.todo_due_date_help') ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Completion Notes (hidden by default, shown when status is completed) -->
                                    <div class="mb-4" id="completionNotesSection" style="display: none;">
                                        <label for="completion_notes" class="form-label fw-semibold">
                                            <i data-feather="file-text" class="icon-sm me-1"></i>
                                            <?= lang('App.todo_completion_notes') ?>
                                        </label>
                                        <textarea class="form-control" id="completion_notes" name="completion_notes" rows="3" 
                                                  placeholder="<?= lang('App.todo_completion_notes_placeholder') ?>"><?= old('completion_notes') ?></textarea>
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
                                        <select class="form-select native-select" id="todo_status" name="status" required data-choices-disable="true">
                                            <option value="pending" <?= old('status', 'pending') == 'pending' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_status_pending') ?>
                                            </option>
                                            <option value="in_progress" <?= old('status') == 'in_progress' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_status_in_progress') ?>
                                            </option>
                                            <option value="completed" <?= old('status') == 'completed' ? 'selected' : '' ?>>
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
                                        <select class="form-select native-select" id="priority" name="priority" required data-choices-disable="true">
                                            <option value="low" <?= old('priority') == 'low' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_low') ?>
                                            </option>
                                            <option value="medium" <?= old('priority', 'medium') == 'medium' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_medium') ?>
                                            </option>
                                            <option value="high" <?= old('priority') == 'high' ? 'selected' : '' ?>>
                                                <?= lang('App.todo_priority_high') ?>
                                            </option>
                                            <option value="urgent" <?= old('priority') == 'urgent' ? 'selected' : '' ?>>
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
                                                <div class="d-flex gap-2" id="statusBadges">
                                                    <span class="badge bg-warning" id="statusBadge">
                                                        <?= lang('App.todo_status_pending') ?>
                                                    </span>
                                                    <span class="badge bg-info" id="priorityBadge">
                                                        <?= lang('App.todo_priority_medium') ?>
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
                                                    <td class="text-muted"><?= date('M j, Y H:i') ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.estimated') ?>:</td>
                                                    <td class="text-muted" id="estimatedDisplay">-- <?= lang('App.hours') ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.due_date') ?>:</td>
                                                    <td class="text-muted" id="dueDateDisplay">--</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium"><?= lang('App.todo_category') ?>:</td>
                                                    <td class="text-muted" id="categoryDisplay">--</td>
                                                </tr>
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
                                        <button type="button" class="btn btn-success btn-sm" id="markCompleted">
                                            <i data-feather="check" class="icon-sm me-1"></i>
                                            <?= lang('App.mark_as_completed') ?>
                                        </button>
                                        
                                        <button type="button" class="btn btn-info btn-sm" id="setPriorityHigh">
                                            <i data-feather="arrow-up" class="icon-sm me-1"></i>
                                            Set High Priority
                                        </button>
                                        
                                        <button type="button" class="btn btn-secondary btn-sm" id="clearForm">
                                            <i data-feather="rotate-ccw" class="icon-sm me-1"></i>
                                            Clear Form
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
                                        <button type="submit" name="save_and_new" value="1" class="btn btn-success">
                                            <i data-feather="plus" class="icon-sm me-1"></i>
                                            <?= lang('App.save_and_create_new') ?>
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="createBtn">
                                            <i data-feather="save" class="icon-sm me-1"></i>
                                            <?= lang('App.create_todo') ?>
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
    console.log('🎯 TODO Create - Modern Velzon Theme Loaded');
    
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Prevent Choices.js from auto-initializing on our native selects
    $('.native-select').each(function() {
        $(this).removeClass('data-choices');
        $(this).removeAttr('data-choices');
    });
    
    // Set default values and update display immediately
    setTimeout(() => {
        $('#todo_status').val('pending').trigger('change');
        $('#priority').val('medium').trigger('change');
        updateStatusIndicators();
        updateInfoDisplay();
    }, 100);
    
    // Form validation
    const form = document.getElementById('createTodoForm');
    
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
        updateStatusIndicators();
    });
    
    // Priority change handler
    $('#priority').on('change', function() {
        updateStatusIndicators();
    });
    
    // Real-time field updates
    $('#estimated_hours').on('input change', function() {
        updateInfoDisplay();
    });
    
    $('#due_date').on('input change', function() {
        updateInfoDisplay();
    });
    
    $('#category').on('input change', function() {
        updateInfoDisplay();
    });
    
    // Quick action buttons
    $('#markCompleted').on('click', function() {
        $('#todo_status').val('completed').trigger('change');
        showToast('info', 'Status updated to completed');
    });
    
    $('#setPriorityHigh').on('click', function() {
        $('#priority').val('high').trigger('change');
        showToast('info', 'Priority set to high');
    });
    
    $('#clearForm').on('click', function() {
        Swal.fire({
            title: 'Clear Form?',
            text: 'This will clear all form data. Are you sure?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, clear it',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                form.reset();
                $('#todo_status').val('pending').trigger('change');
                $('#priority').val('medium').trigger('change');
                updateStatusIndicators();
                updateInfoDisplay();
                showToast('success', 'Form cleared successfully');
            }
        });
    });

    // Update status indicators
    function updateStatusIndicators() {
        const status = $('#todo_status').val();
        const priority = $('#priority').val();
        
        const statusColors = {
            'pending': 'warning',
            'in_progress': 'info',
            'completed': 'success'
        };
        
        const priorityColors = {
            'low': 'secondary',
            'medium': 'info',
            'high': 'warning',
            'urgent': 'danger'
        };
        
        const statusLabels = {
            'pending': '<?= lang('App.todo_status_pending') ?>',
            'in_progress': '<?= lang('App.todo_status_in_progress') ?>',
            'completed': '<?= lang('App.todo_status_completed') ?>'
        };
        
        const priorityLabels = {
            'low': '<?= lang('App.todo_priority_low') ?>',
            'medium': '<?= lang('App.todo_priority_medium') ?>',
            'high': '<?= lang('App.todo_priority_high') ?>',
            'urgent': '<?= lang('App.todo_priority_urgent') ?>'
        };
        
        $('#statusBadge').removeClass('bg-warning bg-info bg-success')
                        .addClass('bg-' + statusColors[status])
                        .text(statusLabels[status]);
        
        $('#priorityBadge').removeClass('bg-secondary bg-info bg-warning bg-danger')
                          .addClass('bg-' + priorityColors[priority])
                          .text(priorityLabels[priority]);
        }
    
    // Update info display
    function updateInfoDisplay() {
        const hours = parseFloat($('#estimated_hours').val()) || 0;
        const dueDate = $('#due_date').val();
        const category = $('#category').val();
        
        $('#estimatedDisplay').text(hours > 0 ? hours + ' <?= lang('App.hours') ?>' : '-- <?= lang('App.hours') ?>');
        $('#dueDateDisplay').text(dueDate || '--');
        $('#categoryDisplay').text(category || '--');
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
            delay: 3000
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
    
    // Character counter for title
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
    $('#createBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true)
           .html('<i class="spinner-border spinner-border-sm me-1"></i> <?= lang('App.creating') ?>...');
        
        // Re-enable button after 3 seconds (in case form doesn't submit)
        setTimeout(() => {
            btn.prop('disabled', false).html(originalText);
        }, 3000);
    });
    
    // Auto-focus on title field
    setTimeout(() => {
        $('#title').focus();
    }, 200);
    
    // Final check to ensure native selects work properly
    setTimeout(() => {
        // Remove any Choices.js instances that might have been created
        $('.native-select').each(function() {
            const $this = $(this);
            const $choicesWrapper = $this.siblings('.choices');
            
            if ($choicesWrapper.length) {
                $choicesWrapper.remove();
                $this.show();
            }
            
            // Ensure the select is visible and functional
            $this.prop('disabled', false).show();
        });
        
        // Re-trigger change events to ensure proper display
        $('#todo_status, #priority').trigger('change');
    }, 500);
});
</script>

<!-- Load SweetAlert2 if not already loaded -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<?= $this->endSection() ?>
