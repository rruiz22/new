<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Dashboard - Todo Application<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('date'); ?>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?= $pendingTodos ?>"><?= $pendingTodos ?></span></h4>
                        <a href="<?= base_url('todos') ?>" class="text-decoration-underline">View all tasks</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded fs-3">
                            <i class="ri-todo-line"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Completed Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?= $completedTodos ?>"><?= $completedTodos ?></span></h4>
                        <a href="<?= base_url('todos') ?>" class="text-decoration-underline">View all tasks</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded fs-3">
                            <i class="ri-checkbox-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">High Priority Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?= $highPriorityTodos ?>"><?= $highPriorityTodos ?></span></h4>
                        <a href="<?= base_url('todos') ?>" class="text-decoration-underline">View all tasks</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger rounded fs-3">
                            <i class="ri-alert-line"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Staff Users</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?= $totalStaff ?>"><?= $totalStaff ?></span> / <?= $totalUsers ?></h4>
                        <a href="<?= base_url('staff') ?>" class="text-decoration-underline">View all staff</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded fs-3">
                            <i class="ri-user-line"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Upcoming Tasks</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-muted">
                                <th scope="col">Task</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Due Date</th>
                                <th scope="col" style="width: 16%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($upcomingTodos)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No upcoming tasks</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($upcomingTodos as $todo): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('todos/edit/' . $todo['id']) ?>" class="fw-medium"><?= esc($todo['title']) ?></a>
                                        </td>
                                        <td>
                                            <?php
                                            $priorityClass = [
                                                'low' => 'bg-success',
                                                'medium' => 'bg-warning',
                                                'high' => 'bg-danger'
                                            ];
                                            ?>
                                            <span class="badge <?= $priorityClass[$todo['priority']] ?>">
                                                <?= ucfirst($todo['priority']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= $todo['formatted_due_date'] ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('todos/toggleStatus/' . $todo['id']) ?>" class="btn btn-sm btn-soft-success">
                                                <i class="ri-checkbox-circle-line"></i> Complete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Recent Activities</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="list-group">
                        <?php foreach ($recentActivities as $activity): ?>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <?php
                                        $iconClass = '';
                                        switch ($activity['type']) {
                                            case 'todo_created':
                                                $iconClass = 'ri-add-circle-line text-success';
                                                break;
                                            case 'todo_completed':
                                                $iconClass = 'ri-checkbox-circle-line text-primary';
                                                break;
                                            case 'todo_updated':
                                                $iconClass = 'ri-edit-line text-warning';
                                                break;
                                            case 'todo_deleted':
                                                $iconClass = 'ri-delete-bin-line text-danger';
                                                break;
                                            default:
                                                $iconClass = 'ri-information-line text-info';
                                        }
                                        ?>
                                        <div class="avatar-xs">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="<?= $iconClass ?>"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= esc($activity['description']) ?></h6>
                                        <p class="text-muted mb-0"><?= $activity['formatted_date'] ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 