<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.todos') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.todos') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.todos') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.todos') ?></h4>
        <div class="flex-shrink-0">
            <button class="btn btn-primary" id="addTodoBtn">
                <i data-feather="plus" class="icon-sm me-1"></i>
                <span class="d-none d-sm-inline"><?= lang('App.add_todo') ?></span>
                <span class="d-inline d-sm-none"><?= lang('App.create') ?></span>
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#dashboard-tab" role="tab">
                    <span><i data-feather="home" class="icon-sm me-1"></i> <?= lang('App.dashboard') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pending-tab" role="tab">
                    <span>
                        <i data-feather="clock" class="icon-sm me-1"></i> <?= lang('App.pending_todos') ?>
                        <span id="pendingTodosBadge" class="badge bg-warning ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#overdue-tab" role="tab">
                    <span>
                        <i data-feather="alert-triangle" class="icon-sm me-1"></i> <?= lang('App.overdue_todos') ?>
                        <span id="overdueTodosBadge" class="badge bg-danger ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#completed-tab" role="tab">
                    <span>
                        <i data-feather="check-circle" class="icon-sm me-1"></i> <?= lang('App.completed_todos') ?>
                        <span id="completedTodosBadge" class="badge bg-success ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#insights-tab" role="tab">
                    <span><i data-feather="bar-chart-2" class="icon-sm me-1"></i> <?= lang('App.productivity_insights') ?></span>
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-3">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard-tab" role="tabpanel">
                <!-- Statistics Cards Row -->
                <div class="row mb-4">
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_total') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['total'] ?? 0 ?>"><?= $stats['total'] ?? 0 ?></span>
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.todo_stats') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-primary rounded fs-3">
                                            <i data-feather="list" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_pending') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['pending'] ?? 0 ?>"><?= $stats['pending'] ?? 0 ?></span>
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.todo_status_pending') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning rounded fs-3">
                                            <i data-feather="clock" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_in_progress') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['in_progress'] ?? 0 ?>"><?= $stats['in_progress'] ?? 0 ?></span>
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.todo_status_in_progress') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info rounded fs-3">
                                            <i data-feather="play" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_completed') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['completed'] ?? 0 ?>"><?= $stats['completed'] ?? 0 ?></span>
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.todo_status_completed') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success rounded fs-3">
                                            <i data-feather="check-circle" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_overdue') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['overdue'] ?? 0 ?>"><?= $stats['overdue'] ?? 0 ?></span>
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.overdue_todos') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-danger rounded fs-3">
                                            <i data-feather="alert-triangle" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.todo_stats_completion_rate') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value" data-target="<?= $stats['completion_rate'] ?? 0 ?>"><?= $stats['completion_rate'] ?? 0 ?></span>%
                                        </h4>
                                        <span class="text-muted fs-13"><?= lang('App.productivity_insights') ?></span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-secondary rounded fs-3">
                                            <i data-feather="trending-up" class="text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.todo_filters') ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('todos') ?>" id="filtersForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label"><?= lang('App.todo_search') ?></label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="todoSearchInput" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="<?= lang('App.todo_search') ?>" autocomplete="off">
                                        <i data-feather="search" class="icon-sm position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                                        
                                        <!-- Search Results Dropdown -->
                                        <div id="todoSearchResults" class="dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;">
                                            <div class="dropdown-header">
                                                <span><?= lang('App.search_results') ?></span>
                                                <span id="searchResultsCount" class="badge bg-primary ms-2">0</span>
                                            </div>
                                            <div id="todoSearchList">
                                                <!-- Search results will be populated here -->
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="dropdown-item-text text-center">
                                                <small class="text-muted"><?= lang('App.search_suggestions') ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label"><?= lang('App.todo_filter_by_status') ?></label>
                                    <select class="form-select" name="status">
                                        <option value=""><?= lang('App.select') ?></option>
                                        <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>><?= lang('App.todo_status_pending') ?></option>
                                        <option value="in_progress" <?= ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>><?= lang('App.todo_status_in_progress') ?></option>
                                        <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>><?= lang('App.todo_status_completed') ?></option>
                                        <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>><?= lang('App.todo_status_cancelled') ?></option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label"><?= lang('App.todo_filter_by_priority') ?></label>
                                    <select class="form-select" name="priority">
                                        <option value=""><?= lang('App.select') ?></option>
                                        <option value="low" <?= ($filters['priority'] ?? '') === 'low' ? 'selected' : '' ?>><?= lang('App.todo_priority_low') ?></option>
                                        <option value="medium" <?= ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' ?>><?= lang('App.todo_priority_medium') ?></option>
                                        <option value="high" <?= ($filters['priority'] ?? '') === 'high' ? 'selected' : '' ?>><?= lang('App.todo_priority_high') ?></option>
                                        <option value="urgent" <?= ($filters['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>><?= lang('App.todo_priority_urgent') ?></option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label"><?= lang('App.todo_filter_by_category') ?></label>
                                    <select class="form-select" name="category">
                                        <option value=""><?= lang('App.select') ?></option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= esc($category) ?>" <?= ($filters['category'] ?? '') === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label"><?= lang('App.todo_date_from') ?></label>
                                    <input type="date" class="form-control" name="due_date_from" value="<?= esc($filters['due_date_from'] ?? '') ?>">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label"><?= lang('App.todo_date_to') ?></label>
                                    <input type="date" class="form-control" name="due_date_to" value="<?= esc($filters['due_date_to'] ?? '') ?>">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i data-feather="filter" class="icon-sm me-1"></i><?= lang('App.todo_apply_filters') ?>
                                        </button>
                                        <a href="<?= base_url('todos') ?>" class="btn btn-outline-secondary btn-sm">
                                            <i data-feather="x" class="icon-sm me-1"></i><?= lang('App.todo_clear_filters') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Alert sections for overdue and due today -->
                <?php if (!empty($overdueTodos)): ?>
                    <div class="alert alert-border-left alert-warning alert-dismissible fade show" role="alert">
                        <i data-feather="alert-triangle" class="icon-sm me-2 align-middle"></i>
                        <strong><?= lang('App.overdue_todos') ?></strong> - <?= count($overdueTodos) ?> <?= count($overdueTodos) === 1 ? lang('App.todo') : lang('App.todos') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <hr class="my-3">
                        <div class="row">
                            <?php foreach (array_slice($overdueTodos, 0, 3) as $todo): ?>
                                <div class="col-md-4">
                                    <div class="card border border-warning mb-2">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-1"><?= esc($todo['title']) ?></h6>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm edit-todo" data-id="<?= $todo['id'] ?>" title="<?= lang('App.edit_todo') ?>">
                                                        <i data-feather="edit" class="icon-sm"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success btn-sm complete-todo" data-id="<?= $todo['id'] ?>" title="<?= lang('App.complete_todo') ?>">
                                                        <i data-feather="check" class="icon-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <i data-feather="clock" class="icon-sm me-1"></i>
                                                <?= $todo['days_overdue'] ?? 0 ?> days overdue
                                            </small>
                                            <?php if (!empty($todo['description'])): ?>
                                                <p class="small text-muted mt-2 mb-0"><?= esc(substr($todo['description'], 0, 60)) ?><?= strlen($todo['description']) > 60 ? '...' : '' ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($dueTodayTodos)): ?>
                    <div class="alert alert-border-left alert-info alert-dismissible fade show" role="alert">
                        <i data-feather="calendar" class="icon-sm me-2 align-middle"></i>
                        <strong><?= lang('App.due_today_todos') ?></strong> - <?= count($dueTodayTodos) ?> <?= count($dueTodayTodos) === 1 ? lang('App.todo') : lang('App.todos') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <hr class="my-3">
                        <div class="row">
                            <?php foreach (array_slice($dueTodayTodos, 0, 3) as $todo): ?>
                                <div class="col-md-4">
                                    <div class="card border border-info mb-2">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-1"><?= esc($todo['title']) ?></h6>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm edit-todo" data-id="<?= $todo['id'] ?>" title="<?= lang('App.edit_todo') ?>">
                                                        <i data-feather="edit" class="icon-sm"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success btn-sm complete-todo" data-id="<?= $todo['id'] ?>" title="<?= lang('App.complete_todo') ?>">
                                                        <i data-feather="check" class="icon-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <i data-feather="calendar" class="icon-sm me-1"></i>
                                                <?= lang('App.due_today') ?>
                                            </small>
                                            <?php if (!empty($todo['description'])): ?>
                                                <p class="small text-muted mt-2 mb-0"><?= esc(substr($todo['description'], 0, 60)) ?><?= strlen($todo['description']) > 60 ? '...' : '' ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pending Tasks Tab -->
            <div class="tab-pane fade" id="pending-tab" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.pending_todos') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pendingTodos)): ?>
                            <div class="row">
                                <?php foreach ($pendingTodos as $todo): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 border-start border-<?= $todo['priority'] === 'urgent' ? 'danger' : ($todo['priority'] === 'high' ? 'warning' : ($todo['priority'] === 'medium' ? 'info' : 'secondary')) ?> border-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0"><?= esc($todo['title']) ?></h6>
                                                    <span class="badge bg-<?= $todo['priority'] === 'urgent' ? 'danger' : ($todo['priority'] === 'high' ? 'warning' : ($todo['priority'] === 'medium' ? 'info' : 'secondary')) ?>">
                                                        <?= lang('App.todo_priority_' . $todo['priority']) ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if (!empty($todo['description'])): ?>
                                                    <p class="card-text small text-muted"><?= esc(substr($todo['description'], 0, 100)) ?><?= strlen($todo['description']) > 100 ? '...' : '' ?></p>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge bg-<?= $todo['status'] === 'pending' ? 'warning' : ($todo['status'] === 'in_progress' ? 'info' : 'success') ?>">
                                                            <?= lang('App.todo_status_' . $todo['status']) ?>
                                                        </span>
                                                        <?php if (!empty($todo['category'])): ?>
                                                            <span class="badge bg-light text-dark"><?= esc($todo['category']) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary btn-sm edit-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="edit" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm complete-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="check" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm delete-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="trash-2" class="icon-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <?php if (!empty($todo['formatted_due_date'])): ?>
                                                    <div class="mt-2">
                                                        <small class="text-<?= $todo['is_overdue'] ? 'danger' : 'muted' ?>">
                                                            <i data-feather="calendar" class="icon-sm me-1"></i>
                                                            <?= $todo['formatted_due_date'] ?>
                                                            <?php if ($todo['is_overdue']): ?>
                                                                (<?= sprintf(lang('App.days_overdue'), $todo['days_overdue'] ?? 0) ?>)
                                                            <?php elseif (isset($todo['days_until_due'])): ?>
                                                                (<?= sprintf(lang('App.due_in_days'), $todo['days_until_due']) ?>)
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i data-feather="check-circle" class="icon-lg text-muted mb-3"></i>
                                <h5 class="text-muted"><?= lang('App.todo_empty') ?></h5>
                                <p class="text-muted"><?= lang('App.no_results') ?></p>
                                <button class="btn btn-primary" id="addTodoFromEmpty">
                                    <i data-feather="plus" class="icon-sm me-1"></i> <?= lang('App.add_todo') ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks Tab -->
            <div class="tab-pane fade" id="overdue-tab" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.overdue_todos') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($overdueTodos)): ?>
                            <div class="row">
                                <?php foreach ($overdueTodos as $todo): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 border-start border-danger border-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0"><?= esc($todo['title']) ?></h6>
                                                    <span class="badge bg-danger">
                                                        <?= sprintf(lang('App.days_overdue'), $todo['days_overdue'] ?? 0) ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if (!empty($todo['description'])): ?>
                                                    <p class="card-text small text-muted"><?= esc(substr($todo['description'], 0, 100)) ?><?= strlen($todo['description']) > 100 ? '...' : '' ?></p>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge bg-<?= $todo['priority'] === 'urgent' ? 'danger' : ($todo['priority'] === 'high' ? 'warning' : ($todo['priority'] === 'medium' ? 'info' : 'secondary')) ?>">
                                                            <?= lang('App.todo_priority_' . $todo['priority']) ?>
                                                        </span>
                                                        <?php if (!empty($todo['category'])): ?>
                                                            <span class="badge bg-light text-dark"><?= esc($todo['category']) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary btn-sm edit-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="edit" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm complete-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="check" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm delete-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="trash-2" class="icon-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i data-feather="alert-triangle" class="icon-sm me-1"></i>
                                                        <?= $todo['formatted_due_date'] ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i data-feather="check-circle" class="icon-lg text-success mb-3"></i>
                                <h5 class="text-success"><?= lang('App.no_overdue_todos') ?></h5>
                                <p class="text-muted"><?= lang('App.great_job_staying_on_track') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Tab -->
            <div class="tab-pane fade" id="completed-tab" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.completed_todos') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($completedTodos)): ?>
                            <div class="row">
                                <?php foreach ($completedTodos as $todo): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0 text-decoration-line-through"><?= esc($todo['title']) ?></h6>
                                                    <span class="badge bg-success">
                                                        <?= lang('App.todo_status_completed') ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if (!empty($todo['description'])): ?>
                                                    <p class="card-text small text-muted"><?= esc(substr($todo['description'], 0, 80)) ?><?= strlen($todo['description']) > 80 ? '...' : '' ?></p>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <?php if (!empty($todo['category'])): ?>
                                                            <span class="badge bg-light text-dark"><?= esc($todo['category']) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-secondary btn-sm view-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="eye" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning btn-sm reopen-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="rotate-ccw" class="icon-sm"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm delete-todo" data-id="<?= $todo['id'] ?>">
                                                            <i data-feather="trash-2" class="icon-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($completedTodos) > 6): ?>
                                <div class="text-center mt-3">
                                    <p class="text-muted"><?= sprintf(lang('App.showing'), 6, count($completedTodos)) ?> <?= lang('App.completed_todos') ?></p>
                                    <button class="btn btn-outline-primary" id="loadMoreCompleted">
                                        <?= lang('App.load_more') ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i data-feather="inbox" class="icon-lg text-muted mb-3"></i>
                                <h5 class="text-muted"><?= lang('App.no_completed_todos') ?></h5>
                                <p class="text-muted"><?= lang('App.complete_some_tasks_to_see_them_here') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Insights Tab -->
            <div class="tab-pane fade" id="insights-tab" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.productivity_insights') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                            <i data-feather="target" class="text-primary"></i>
                                        </span>
                                    </div>
                                    <h4 class="text-primary"><?= $insights['completed_tasks'] ?? 0 ?></h4>
                                    <small class="text-muted"><?= lang('App.insights_completed_tasks') ?></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                            <i data-feather="clock" class="text-info"></i>
                                        </span>
                                    </div>
                                    <h4 class="text-info"><?= $insights['avg_completion_time'] ?? 0 ?> <?= lang('App.insights_hours') ?></h4>
                                    <small class="text-muted"><?= lang('App.insights_avg_completion_time') ?></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                            <i data-feather="trending-up" class="text-success"></i>
                                        </span>
                                    </div>
                                    <h4 class="text-success"><?= $insights['tasks_per_day'] ?? 0 ?></h4>
                                    <small class="text-muted"><?= lang('App.insights_tasks_per_day') ?></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                            <i data-feather="calendar" class="text-warning"></i>
                                        </span>
                                    </div>
                                    <h4 class="text-warning"><?= $insights['most_productive_day'] ?? 'N/A' ?></h4>
                                    <small class="text-muted"><?= lang('App.insights_most_productive_day') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    console.log('🎯 TODO List - Modern Velzon Theme Loaded');
    
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Add Todo Button Event Handler
    $('#addTodoBtn').on('click', function() {
        window.location.href = '<?= base_url('todos/create') ?>';
    });
    
    // Initialize counter animations
    $('.counter-value').each(function() {
        const $this = $(this);
        const target = parseInt($this.data('target')) || 0;
        $({ counter: 0 }).animate({ counter: target }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(Math.ceil(this.counter));
            },
            complete: function() {
                $this.text(target);
            }
        });
    });
    
    // Update tab badges with counts
    updateTabBadges();
    
    // Real-time search functionality
    let searchTimeout;
    const searchInput = $('#todoSearchInput');
    const searchResults = $('#todoSearchResults');
    const searchList = $('#todoSearchList');
    const searchResultsCount = $('#searchResultsCount');
    
    // Search input handler
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideSearchResults();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#todoSearchInput, #todoSearchResults').length) {
            hideSearchResults();
        }
    });
    
    // Show search results on focus if there's a query
    searchInput.on('focus', function() {
        const query = $(this).val().trim();
        if (query.length >= 2) {
            searchResults.show();
            }
    });
    
    // Perform search function
    function performSearch(query) {
        $.ajax({
            url: '<?= base_url('todos/search') ?>',
            type: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.todos) {
                    displaySearchResults(response.todos, query);
                } else {
                    displayNoResults(query);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                displaySearchError();
            }
        });
    }
    
    // Display search results
    function displaySearchResults(todos, query) {
        searchList.empty();
        
        if (todos.length === 0) {
            displayNoResults(query);
            return;
        }
        
        searchResultsCount.text(todos.length);
        
        todos.forEach(function(todo) {
            const statusColor = todo.status === 'completed' ? 'success' : 
                               todo.status === 'in_progress' ? 'info' : 'warning';
            
            const priorityColor = todo.priority === 'urgent' ? 'danger' : 
                                  todo.priority === 'high' ? 'warning' : 
                                  todo.priority === 'medium' ? 'info' : 'secondary';
            
            const dueDateHtml = todo.formatted_due_date ? 
                `<small class="text-muted"><i data-feather="calendar" class="icon-sm me-1"></i>${todo.formatted_due_date}</small>` : '';
            
            const categoryHtml = todo.category ? 
                `<span class="badge bg-light text-dark ms-2">${escapeHtml(todo.category)}</span>` : '';
            
            const searchItem = $(`
                <div class="dropdown-item search-todo-item" data-todo-id="${todo.id}" style="cursor: pointer; border-left: 3px solid var(--bs-${priorityColor});">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${highlightSearchTerm(escapeHtml(todo.title), query)}</h6>
                            ${todo.description ? `<p class="mb-1 small text-muted">${highlightSearchTerm(escapeHtml(todo.description.substring(0, 80)), query)}${todo.description.length > 80 ? '...' : ''}</p>` : ''}
                            <div class="d-flex align-items-center">
                                <span class="badge bg-${statusColor} me-2">${todo.status}</span>
                                <span class="badge bg-${priorityColor} me-2">${todo.priority}</span>
                                ${categoryHtml}
                                ${dueDateHtml}
                            </div>
                        </div>
                        <div class="btn-group btn-group-sm ms-2">
                            <button class="btn btn-outline-primary btn-sm edit-todo" data-id="${todo.id}" title="Edit">
                                <i data-feather="edit" class="icon-sm"></i>
                            </button>
                            ${todo.status !== 'completed' ? 
                                `<button class="btn btn-outline-success btn-sm complete-todo" data-id="${todo.id}" title="Complete">
                                    <i data-feather="check" class="icon-sm"></i>
                                </button>` : 
                                `<button class="btn btn-outline-warning btn-sm reopen-todo" data-id="${todo.id}" title="Reopen">
                                    <i data-feather="rotate-ccw" class="icon-sm"></i>
                                </button>`
                            }
                        </div>
                    </div>
                </div>
            `);
            
            searchList.append(searchItem);
        });
        
        searchResults.show();
        
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Display no results message
    function displayNoResults(query) {
        searchList.html(`
            <div class="dropdown-item-text text-center py-3">
                <i data-feather="search" class="icon-lg text-muted mb-2"></i>
                <p class="mb-0 text-muted">No results found for "${escapeHtml(query)}"</p>
                <small class="text-muted">Try searching with different keywords</small>
            </div>
        `);
        
        searchResultsCount.text(0);
        searchResults.show();
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Display search error
    function displaySearchError() {
        searchList.html(`
            <div class="dropdown-item-text text-center py-3">
                <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                <p class="mb-0 text-danger">Search error occurred</p>
                <small class="text-muted">Please try again</small>
            </div>
        `);
        
        searchResultsCount.text(0);
        searchResults.show();
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Hide search results
    function hideSearchResults() {
        searchResults.hide();
    }
    
    // Highlight search terms in text
    function highlightSearchTerm(text, term) {
        if (!term || !text) return text;
        
        const regex = new RegExp(`(${escapeRegExp(term)})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    // Escape RegExp special characters
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    // Handle search result item clicks
    $(document).on('click', '.search-todo-item', function(e) {
        // Don't trigger if clicking on buttons
        if ($(e.target).closest('.btn').length) {
            return;
        }
        
        const todoId = $(this).data('todo-id');
        
        // Navigate to edit page or show details
        window.location.href = '<?= base_url('todos/edit') ?>/' + todoId;
    });
    
    // Edit TODO functionality - Navigate to edit page
    $(document).on('click', '.edit-todo', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const todoId = $(this).data('id');
        window.location.href = '<?= base_url('todos/edit') ?>/' + todoId;
    });
    
    // View TODO functionality - Navigate to edit page (for completed todos)
    $(document).on('click', '.view-todo', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const todoId = $(this).data('id');
        window.location.href = '<?= base_url('todos/edit') ?>/' + todoId;
    });
    
    // Complete TODO functionality with SweetAlert and real-time update
    $(document).on('click', '.complete-todo', function() {
        const todoId = $(this).data('id');
        const todoCard = $(this).closest('.card');
        const todoContainer = $(this).closest('[class*="col-"]');
        
        Swal.fire({
            title: '<?= lang('App.confirm_complete_todo') ?>',
            text: '<?= lang('App.complete_todo_confirmation') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_complete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('todos/toggleStatus') ?>/' + todoId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success && response.todo) {
                            // Remove todo from current tab
                            todoContainer.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Add to completed tab if the todo is completed
                                if (response.newStatus === 'completed') {
                                    const completedHtml = generateCompletedTodoHtml(response.todo);
                                    const completedContainer = $('#completed-tab .row');
                                    
                                    // Check if "no completed todos" message exists and remove it
                                    const noDataMessage = $('#completed-tab .text-center.py-4');
                                    if (noDataMessage.length > 0) {
                                        noDataMessage.remove();
                                    }
                                    
                                    // Add row container if it doesn't exist
                                    if (completedContainer.length === 0) {
                                        $('#completed-tab .card-body').append('<div class="row"></div>');
                                    }
                                    
                                    // Add the completed todo with animation
                                    const newElement = $(completedHtml).hide();
                                    $('#completed-tab .row').prepend(newElement);
                                    newElement.fadeIn(300);
                                    
                                    // Re-initialize Feather icons
                                    if (typeof feather !== 'undefined') {
                                        feather.replace();
                                    }
                                }
                                
                                updateTabBadges();
                                updateStatsCounters();
                            });
                            showToast('success', response.message || '<?= lang('App.todo_completed_successfully') ?>');
                        } else {
                            showToast('error', response.message || '<?= lang('App.error_completing_todo') ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        showToast('error', '<?= lang('App.error_completing_todo') ?>');
                    }
                });
            }
        });
    });
    
    // Reopen TODO functionality with real-time update
    $(document).on('click', '.reopen-todo', function() {
        const todoId = $(this).data('id');
        const todoCard = $(this).closest('.card');
        const todoContainer = $(this).closest('[class*="col-"]');
        
        Swal.fire({
            title: '<?= lang('App.confirm_reopen_todo') ?>',
            text: '<?= lang('App.reopen_todo_confirmation') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_reopen') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('todos/toggleStatus') ?>/' + todoId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success && response.todo) {
                            // Remove todo from completed tab
                            todoContainer.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Add to pending tab if the todo is reopened
                                if (response.newStatus === 'pending') {
                                    const pendingHtml = generatePendingTodoHtml(response.todo);
                                    const pendingContainer = $('#pending-tab .row');
                                
                                    // Check if "no pending todos" message exists and remove it
                                    const noDataMessage = $('#pending-tab .text-center.py-4');
                                    if (noDataMessage.length > 0) {
                                        noDataMessage.remove();
                                    }
                                    
                                    // Add row container if it doesn't exist
                                    if (pendingContainer.length === 0) {
                                        $('#pending-tab .card-body').append('<div class="row"></div>');
                                    }
                                    
                                    // Add the reopened todo with animation
                                    const newElement = $(pendingHtml).hide();
                                    $('#pending-tab .row').prepend(newElement);
                                    newElement.fadeIn(300);
                                    
                                    // Re-initialize Feather icons
                                    if (typeof feather !== 'undefined') {
                                        feather.replace();
                                    }
                                    
                                    // Switch to pending tab after a brief delay
                                setTimeout(() => {
                                    $('a[href="#pending-tab"]').tab('show');
                                }, 500);
                                }
                                
                                updateTabBadges();
                                updateStatsCounters();
                            });
                            showToast('success', response.message || '<?= lang('App.todo_reopened_successfully') ?>');
                        } else {
                            showToast('error', response.message || '<?= lang('App.error_reopening_todo') ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        showToast('error', '<?= lang('App.error_reopening_todo') ?>');
                    }
                });
            }
        });
    });
    
    // Delete TODO functionality with SweetAlert
    $(document).on('click', '.delete-todo', function() {
        const todoId = $(this).data('id');
        const todoCard = $(this).closest('.card');
        const todoContainer = $(this).closest('[class*="col-"]');
        
        Swal.fire({
            title: '<?= lang('App.confirm_delete_todo') ?>',
            text: '<?= lang('App.delete_todo_confirmation') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('todos/delete') ?>/' + todoId,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            todoContainer.fadeOut(300, function() {
                                $(this).remove();
                                updateTabBadges();
                                updateStatsCounters();
                            });
                            showToast('success', response.message || '<?= lang('App.todo_deleted_successfully') ?>');
                        } else {
                            showToast('error', response.message || '<?= lang('App.error_deleting_todo') ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        showToast('error', '<?= lang('App.error_deleting_todo') ?>');
                    }
                });
            }
        });
    });
    
    // Load more completed todos
    $('#loadMoreCompleted').on('click', function() {
        // Implementation for loading more completed todos
        showToast('info', '<?= lang('App.loading_more_todos') ?>');
    });
    
    // Tab change handler
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const target = $(e.target).attr('href');
        
        // Re-initialize Feather icons when tab content is shown
        setTimeout(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }, 100);
        
        // Update URL hash for tab navigation
        if (history.pushState) {
            history.pushState(null, null, target);
        }
    });
    
    // Handle browser back/forward for tabs
    $(window).on('popstate', function() {
        const hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
    });
    
    // Initialize tab from URL hash
    const hash = window.location.hash;
    if (hash) {
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    }
    
    // Update tab badges with current counts
    function updateTabBadges() {
        // Update pending badge - count todo cards in pending tab
        const pendingCount = $('#pending-tab .row > div[class*="col-"]').length;
        updateBadge('#pendingTodosBadge', pendingCount);
        
        // Update overdue badge - count todo cards in overdue tab
        const overdueCount = $('#overdue-tab .row > div[class*="col-"]').length;
        updateBadge('#overdueTodosBadge', overdueCount);
        
        // Update completed badge - count todo cards in completed tab
        const completedCount = $('#completed-tab .row > div[class*="col-"]').length;
        updateBadge('#completedTodosBadge', completedCount);
    }
    
    // Helper function to update individual badge
    function updateBadge(selector, count) {
        const badge = $(selector);
        if (count > 0) {
            badge.text(count).show();
        } else {
            badge.hide();
        }
    }
    
    // Generate HTML for pending todo
    function generatePendingTodoHtml(todo) {
        const priorityColor = todo.priority === 'urgent' ? 'danger' : 
                             todo.priority === 'high' ? 'warning' : 
                             todo.priority === 'medium' ? 'info' : 'secondary';
        
        const statusColor = todo.status === 'pending' ? 'warning' : 
                           todo.status === 'in_progress' ? 'info' : 'success';
        
        const priorityLabels = {
            'urgent': '<?= lang('App.todo_priority_urgent') ?>',
            'high': '<?= lang('App.todo_priority_high') ?>',
            'medium': '<?= lang('App.todo_priority_medium') ?>',
            'low': '<?= lang('App.todo_priority_low') ?>'
        };
        
        const statusLabels = {
            'pending': '<?= lang('App.todo_status_pending') ?>',
            'in_progress': '<?= lang('App.todo_status_in_progress') ?>',
            'completed': '<?= lang('App.todo_status_completed') ?>'
        };
        
        const dueDateHtml = todo.formatted_due_date ? `
            <div class="mt-2">
                <small class="text-${todo.is_overdue ? 'danger' : 'muted'}">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    ${todo.formatted_due_date}
                    ${todo.is_overdue ? 
                        `(${todo.days_overdue || 0} days overdue)` : 
                        (todo.days_until_due ? `(Due in ${todo.days_until_due} days)` : '')
                    }
                </small>
            </div>` : '';
        
        const categoryHtml = todo.category ? `<span class="badge bg-light text-dark">${escapeHtml(todo.category)}</span>` : '';
        
        return `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 border-start border-${priorityColor} border-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${escapeHtml(todo.title)}</h6>
                            <span class="badge bg-${priorityColor}">
                                ${priorityLabels[todo.priority] || todo.priority}
                            </span>
                        </div>
                        
                        ${todo.description ? `<p class="card-text small text-muted">${escapeHtml(todo.description.substring(0, 100))}${todo.description.length > 100 ? '...' : ''}</p>` : ''}
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-${statusColor}">
                                    ${statusLabels[todo.status] || todo.status}
                                </span>
                                ${categoryHtml}
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary btn-sm edit-todo" data-id="${todo.id}">
                                    <i data-feather="edit" class="icon-sm"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm complete-todo" data-id="${todo.id}">
                                    <i data-feather="check" class="icon-sm"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-todo" data-id="${todo.id}">
                                    <i data-feather="trash-2" class="icon-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        ${dueDateHtml}
                    </div>
                </div>
            </div>`;
    }
    
    // Generate HTML for completed todo
    function generateCompletedTodoHtml(todo) {
        const categoryHtml = todo.category ? `<span class="badge bg-light text-dark">${escapeHtml(todo.category)}</span>` : '';
        
        return `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0 text-decoration-line-through">${escapeHtml(todo.title)}</h6>
                            <span class="badge bg-success">
                                <?= lang('App.todo_status_completed') ?>
                            </span>
                        </div>
                        
                        ${todo.description ? `<p class="card-text small text-muted">${escapeHtml(todo.description.substring(0, 80))}${todo.description.length > 80 ? '...' : ''}</p>` : ''}
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                ${categoryHtml}
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary btn-sm view-todo" data-id="${todo.id}">
                                    <i data-feather="eye" class="icon-sm"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-sm reopen-todo" data-id="${todo.id}">
                                    <i data-feather="rotate-ccw" class="icon-sm"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-todo" data-id="${todo.id}">
                                    <i data-feather="trash-2" class="icon-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Update statistics counters
    function updateStatsCounters() {
        $.ajax({
            url: '<?= base_url('todos/getStats') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.stats) {
                    const stats = response.stats;
                    
                    // Update each specific stat
                    $('.counter-value').each(function() {
                        const $this = $(this);
                        const parent = $this.closest('.card-body');
                        const textElement = parent.find('.text-uppercase');
                        
                        if (textElement.text().toLowerCase().includes('total')) {
                            updateCounter($this, stats.total);
                        } else if (textElement.text().toLowerCase().includes('pending')) {
                            updateCounter($this, stats.pending);
                        } else if (textElement.text().toLowerCase().includes('progress')) {
                            updateCounter($this, stats.in_progress);
                        } else if (textElement.text().toLowerCase().includes('completed')) {
                            updateCounter($this, stats.completed);
                        } else if (textElement.text().toLowerCase().includes('overdue')) {
                            updateCounter($this, stats.overdue);
                        } else if (textElement.text().toLowerCase().includes('rate')) {
                            updateCounter($this, stats.completion_rate);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating stats:', {xhr, status, error});
            }
        });
    }
    
    // Helper function to animate counter update
    function updateCounter($element, newValue) {
        if (!$element.length) return;
        
        const currentValue = parseInt($element.text()) || 0;
        $({ counter: currentValue }).animate({ counter: newValue }, {
            duration: 500,
            easing: 'swing',
            step: function() {
                $element.text(Math.ceil(this.counter));
            },
            complete: function() {
                $element.text(newValue);
                $element.attr('data-target', newValue);
            }
        });
    }
    
    // Toast notification helper function
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
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        // Append toast to toast container
        $('.toast-container').append(toastHtml);
        
        // Show toast
        const toast = new bootstrap.Toast(document.getElementById(toastId));
        toast.show();
    }
});
</script>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<?= $this->endSection() ?>