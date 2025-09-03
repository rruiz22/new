<!-- Full-screen Vehicle Modal with Notion-style Design and Timer -->
<div class="modal-header" style="background: #fafaf9; border-bottom: 1px solid rgba(55, 53, 47, 0.16); padding: 16px 24px;">
    <div class="d-flex align-items-center flex-grow-1">
        <div class="step-icon step-<?= $vehicle['current_step_color'] ?? 'primary' ?>" style="width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
            <i data-feather="<?= $vehicle['current_step_icon'] ?? 'box' ?>" style="width: 16px; height: 16px;"></i>
        </div>
        <div>
            <h5 class="modal-title" style="margin: 0; font-size: 18px; font-weight: 600; color: #37352f;"><?= esc($vehicle['vin_number']) ?></h5>
            <p style="margin: 0; font-size: 13px; color: rgba(55, 53, 47, 0.65);">
                <?= esc(trim(($vehicle['year'] ?? '') . ' ' . ($vehicle['make'] ?? '') . ' ' . ($vehicle['model'] ?? ''))) ?> • <?= esc($vehicle['client_name'] ?? '') ?>
            </p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" style="padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, 'Apple Color Emoji', Arial, sans-serif;">
    <div class="row g-0" style="min-height: 70vh;">
        <!-- Main Content Area -->
        <div class="col-12 col-lg-8" style="background: #ffffff; padding: 24px;">
            
            <!-- Real-time Timer Section - Notion Compact Style -->
            <div class="timer-section" style="background: #f7f6f3; border: 1px solid rgba(55, 53, 47, 0.16); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="timer-display">
                            <div class="d-flex align-items-center mb-2">
                                <div class="step-indicator" style="width: 24px; height: 24px; background: #2383e2; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                                    <i data-feather="clock" style="width: 12px; height: 12px; color: white;"></i>
                                </div>
                                <span style="font-size: 14px; font-weight: 600; color: #37352f;"><?= lang('GetReady.time_in_step') ?></span>
                            </div>
                            <div class="timer-value" id="vehicleTimer" style="font-size: 28px; font-weight: 700; margin: 0 0 4px 0; font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace; color: #37352f;">
                                <?= $vehicle['current_status']['current_time_formatted'] ?? '00:00:00' ?>
                            </div>
                            <div class="timer-label" style="font-size: 12px; color: rgba(55, 53, 47, 0.65); font-weight: 500;">
                                Current step: <?= esc($vehicle['current_step_name']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="timer-controls">
                            <?php if (isset($vehicle['current_status']['is_paused']) && $vehicle['current_status']['is_paused']): ?>
                            <button class="btn" onclick="resumeTimer(<?= $vehicle['id'] ?>)" 
                                    style="background: #2383e2; border: 1px solid #2383e2; color: #ffffff; font-size: 13px; padding: 8px 16px; border-radius: 6px; margin-right: 8px; font-weight: 500;">
                                <i data-feather="play" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                <?= lang('GetReady.resume') ?>
                            </button>
                            <?php else: ?>
                            <button class="btn" onclick="pauseTimer(<?= $vehicle['id'] ?>)"
                                    style="background: #ffffff; border: 1px solid rgba(55, 53, 47, 0.16); color: #37352f; font-size: 13px; padding: 8px 16px; border-radius: 6px; margin-right: 8px; font-weight: 500;">
                                <i data-feather="pause" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                <?= lang('GetReady.pause') ?>
                            </button>
                            <?php endif; ?>
                            
                            <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); margin-top: 6px; font-weight: 500;">
                                <?= lang('GetReady.total_elapsed') ?>: <?= esc($vehicle['total_time_formatted']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Photo Gallery Section -->
            <?php if (!empty($vehicle['photos_array'])): ?>
            <div class="photos-section" style="margin-bottom: 24px;">
                <h6 style="font-size: 16px; font-weight: 600; color: #37352f; margin: 0 0 12px 0; display: flex; align-items: center;">
                    <i data-feather="camera" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.photo_gallery') ?>
                    <span style="background: rgba(55, 53, 47, 0.16); color: #37352f; font-size: 11px; padding: 2px 6px; border-radius: 10px; margin-left: 8px; font-weight: 500;">
                        <?= count($vehicle['photos_array']) ?>
                    </span>
                </h6>
                
                <div class="photo-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;">
                    <?php foreach (array_slice($vehicle['photos_array'], 0, 8) as $index => $photo): ?>
                    <div class="photo-item" onclick="openPhotoModal('<?= esc($photo) ?>', <?= $index ?>)"
                         style="aspect-ratio: 1; background-image: url('<?= esc($photo) ?>'); background-size: cover; background-position: center; border-radius: 6px; cursor: pointer; border: 1px solid rgba(55, 53, 47, 0.16); transition: all 0.1s ease;">
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (count($vehicle['photos_array']) > 8): ?>
                    <div style="aspect-ratio: 1; background: rgba(55, 53, 47, 0.1); border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 1px solid rgba(55, 53, 47, 0.16);" 
                         onclick="showAllPhotos()">
                        <div style="text-align: center; color: rgba(55, 53, 47, 0.65); font-size: 12px; font-weight: 500;">
                            +<?= count($vehicle['photos_array']) - 8 ?><br>more
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Vehicle Details -->
            <div class="vehicle-details" style="background: #fafaf9; border: 1px solid rgba(55, 53, 47, 0.16); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <h6 style="font-size: 16px; font-weight: 600; color: #37352f; margin: 0 0 16px 0; display: flex; align-items: center;">
                    <i data-feather="info" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.vehicle_info') ?>
                </h6>
                
                <div class="row g-3">
                    <div class="col-6 col-sm-4">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.vin_number') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500;">
                            <?= esc($vehicle['vin_number']) ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($vehicle['stock_number'])): ?>
                    <div class="col-6 col-sm-4">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.stock_number') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500;">
                            <?= esc($vehicle['stock_number']) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($vehicle['mileage'])): ?>
                    <div class="col-6 col-sm-4">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.mileage') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500;">
                            <?= number_format($vehicle['mileage']) ?> mi
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($vehicle['color'])): ?>
                    <div class="col-6 col-sm-4">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.color') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500;">
                            <?= esc($vehicle['color']) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-6 col-sm-4">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.priority') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500;">
                            <span class="status-badge priority-<?= $vehicle['priority'] ?? 'normal' ?>">
                                <?= ucfirst($vehicle['priority'] ?? 'normal') ?>
                            </span>
                        </div>
                    </div>
                    
                    <?php if (!empty($vehicle['current_location'])): ?>
                    <div class="col-12">
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                            <?= lang('GetReady.location') ?>
                        </div>
                        <div style="font-size: 14px; color: #37352f; font-weight: 500; display: flex; align-items: center;">
                            <i data-feather="map-pin" style="width: 14px; height: 14px; margin-right: 4px; color: rgba(55, 53, 47, 0.65);"></i>
                            <?= esc($vehicle['current_location']) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Time Tracking History -->
            <div class="time-history" style="background: #ffffff; border: 1px solid rgba(55, 53, 47, 0.16); border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                <div style="background: #fafaf9; border-bottom: 1px solid rgba(55, 53, 47, 0.16); padding: 16px; font-size: 14px; font-weight: 600; color: #37352f;">
                    <i data-feather="clock" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.time_in_step') ?> History
                </div>
                
                <div style="padding: 0;">
                    <?php if (!empty($vehicle['time_tracking'])): ?>
                        <?php foreach ($vehicle['time_tracking'] as $tracking): ?>
                        <div class="time-tracking-item" style="padding: 12px 16px; border-bottom: 1px solid rgba(55, 53, 47, 0.16); display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center;">
                                <div class="step-icon step-<?= $tracking['step_color'] ?>" style="width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="<?= $tracking['step_icon'] ?>" style="width: 12px; height: 12px;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 500; color: #37352f; margin-bottom: 2px;">
                                        <?= esc($tracking['step_name']) ?>
                                        <?php if ($tracking['is_current']): ?>
                                        <span style="background: rgba(34, 197, 94, 0.15); color: #22c55e; font-size: 10px; padding: 2px 6px; border-radius: 8px; margin-left: 8px; font-weight: 500;">
                                            CURRENT
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="font-size: 12px; color: rgba(55, 53, 47, 0.65);">
                                        Started: <?= date('M j, g:i A', strtotime($tracking['entered_at'])) ?>
                                        <?php if (!empty($tracking['assigned_tech_name'])): ?>
                                        • Assigned to: <?= esc($tracking['assigned_tech_name']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 14px; font-weight: 600; color: #37352f; margin-bottom: 2px;">
                                    <?= esc($tracking['time_formatted']) ?>
                                </div>
                                <?php if ($tracking['is_paused']): ?>
                                <div style="font-size: 10px; color: #f59e0b; font-weight: 500;">
                                    PAUSED
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div style="padding: 24px; text-align: center; color: rgba(55, 53, 47, 0.65); font-size: 14px;">
                        No time tracking history available
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Notes Section -->
            <?php if (!empty($vehicle['notes']) || !empty($vehicle['internal_notes'])): ?>
            <div class="notes-section" style="background: #ffffff; border: 1px solid rgba(55, 53, 47, 0.16); border-radius: 8px; overflow: hidden;">
                <div style="background: #fafaf9; border-bottom: 1px solid rgba(55, 53, 47, 0.16); padding: 16px; font-size: 14px; font-weight: 600; color: #37352f;">
                    <i data-feather="file-text" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.notes') ?>
                </div>
                
                <div style="padding: 16px;">
                    <?php if (!empty($vehicle['notes'])): ?>
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 12px; color: rgba(55, 53, 47, 0.65); font-weight: 500; margin-bottom: 4px;">
                            Customer Notes:
                        </div>
                        <div style="font-size: 14px; color: #37352f; line-height: 1.5;">
                            <?= nl2br(esc($vehicle['notes'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($vehicle['internal_notes'])): ?>
                    <div>
                        <div style="font-size: 12px; color: rgba(55, 53, 47, 0.65); font-weight: 500; margin-bottom: 4px;">
                            Internal Notes:
                        </div>
                        <div style="font-size: 14px; color: #37352f; line-height: 1.5;">
                            <?= nl2br(esc($vehicle['internal_notes'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Sidebar -->
        <div class="col-12 col-lg-4" style="background: #fafaf9; border-left: 1px solid rgba(55, 53, 47, 0.16); padding: 24px;">
            
            <!-- Quick Actions -->
            <div class="quick-actions" style="margin-bottom: 24px;">
                <h6 style="font-size: 14px; font-weight: 600; color: #37352f; margin: 0 0 12px 0; display: flex; align-items: center;">
                    <i data-feather="zap" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.quick_actions') ?>
                </h6>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-notion-primary" onclick="moveToNextStep(<?= $vehicle['id'] ?>)">
                        <i data-feather="arrow-right" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                        <?= lang('GetReady.move_to_next') ?>
                    </button>
                    
                    <button class="btn btn-notion" onclick="updateLocation(<?= $vehicle['id'] ?>)">
                        <i data-feather="map-pin" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                        <?= lang('GetReady.update_location') ?>
                    </button>
                    
                    <button class="btn btn-notion" onclick="uploadPhotos(<?= $vehicle['id'] ?>)">
                        <i data-feather="camera" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                        <?= lang('GetReady.add_photos') ?>
                    </button>
                    
                    <button class="btn btn-notion" onclick="printGetReadySheet(<?= $vehicle['id'] ?>)">
                        <i data-feather="printer" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                        <?= lang('GetReady.print_sheet') ?>
                    </button>
                </div>
            </div>
            
            <!-- Workflow Progress -->
            <div class="workflow-progress" style="margin-bottom: 24px;">
                <h6 style="font-size: 14px; font-weight: 600; color: #37352f; margin: 0 0 12px 0; display: flex; align-items: center;">
                    <i data-feather="trending-up" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    Workflow Progress
                </h6>
                
                <?php 
                $allSteps = [
                    ['name' => 'In Transit', 'slug' => 'in_transit', 'color' => 'primary'],
                    ['name' => 'In Detail', 'slug' => 'in_detail', 'color' => 'info'],
                    ['name' => 'In Service', 'slug' => 'in_service', 'color' => 'warning'],
                    ['name' => 'In Bodyshop', 'slug' => 'in_bodyshop', 'color' => 'danger'],
                    ['name' => 'Completed', 'slug' => 'completed', 'color' => 'success']
                ];
                ?>
                
                <?php foreach ($allSteps as $index => $stepInfo): ?>
                <div class="workflow-step" style="display: flex; align-items: center; padding: 8px 0; position: relative;">
                    <?php 
                    $isCompleted = false;
                    $isCurrent = $stepInfo['slug'] === $vehicle['current_step_slug'];
                    $stepTime = null;
                    
                    // Find time for this step
                    if (!empty($vehicle['time_tracking'])) {
                        foreach ($vehicle['time_tracking'] as $tracking) {
                            if ($tracking['step_slug'] === $stepInfo['slug']) {
                                $isCompleted = !empty($tracking['exited_at']);
                                $stepTime = $tracking['time_formatted'];
                                break;
                            }
                        }
                    }
                    ?>
                    
                    <!-- Step icon -->
                    <div class="step-circle" style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0; <?= $isCompleted ? 'background: #22c55e; color: #ffffff;' : ($isCurrent ? 'background: rgba(35, 131, 226, 0.15); color: #2383e2; border: 2px solid #2383e2;' : 'background: rgba(55, 53, 47, 0.1); color: rgba(55, 53, 47, 0.4);') ?>">
                        <?php if ($isCompleted): ?>
                        <i data-feather="check" style="width: 10px; height: 10px;"></i>
                        <?php elseif ($isCurrent): ?>
                        <div style="width: 6px; height: 6px; background: #2383e2; border-radius: 50%;"></div>
                        <?php else: ?>
                        <div style="width: 6px; height: 6px; background: rgba(55, 53, 47, 0.3); border-radius: 50%;"></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Step info -->
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: 500; color: <?= $isCurrent ? '#2383e2' : '#37352f' ?>; margin-bottom: 2px;">
                            <?= esc($stepInfo['name']) ?>
                            <?php if ($isCurrent): ?>
                            <span style="background: rgba(35, 131, 226, 0.15); color: #2383e2; font-size: 10px; padding: 1px 4px; border-radius: 4px; margin-left: 6px; font-weight: 500;">
                                CURRENT
                            </span>
                            <?php endif; ?>
                        </div>
                        <?php if ($stepTime): ?>
                        <div style="font-size: 11px; color: rgba(55, 53, 47, 0.65);">
                            <?= esc($stepTime) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Connecting line -->
                    <?php if ($index < count($allSteps) - 1): ?>
                    <div style="position: absolute; left: 9px; top: 28px; width: 2px; height: 16px; background: <?= $isCompleted ? '#22c55e' : 'rgba(55, 53, 47, 0.1)' ?>;"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Recent Activities -->
            <div class="recent-activities">
                <h6 style="font-size: 14px; font-weight: 600; color: #37352f; margin: 0 0 12px 0; display: flex; align-items: center;">
                    <i data-feather="activity" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.recent_activities') ?>
                </h6>
                
                <?php if (!empty($vehicle['activities'])): ?>
                <div class="activities-list">
                    <?php foreach (array_slice($vehicle['activities'], 0, 5) as $activity): ?>
                    <div class="activity-item" style="display: flex; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid rgba(55, 53, 47, 0.16);">
                        <div class="activity-icon" style="width: 20px; height: 20px; border-radius: 50%; background: rgba(55, 53, 47, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 8px; flex-shrink: 0;">
                            <i data-feather="<?= $activity['action_icon'] ?? 'activity' ?>" style="width: 10px; height: 10px; color: <?= $activity['action_color'] ?? '#6b7280' ?>;"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 12px; color: #37352f; margin: 0 0 2px 0; line-height: 1.3;">
                                <?= esc($activity['description']) ?>
                            </div>
                            <div style="font-size: 10px; color: rgba(55, 53, 47, 0.65);">
                                <?= esc($activity['time_ago']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 16px; color: rgba(55, 53, 47, 0.65); font-size: 13px;">
                    No recent activities
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<div class="modal-footer" style="background: #fafaf9; border-top: 1px solid rgba(55, 53, 47, 0.16); padding: 16px 24px;">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <span style="font-size: 12px; color: rgba(55, 53, 47, 0.65);">
                Created: <?= date('M j, Y g:i A', strtotime($vehicle['created_at'])) ?>
            </span>
        </div>
        <div>
            <button type="button" class="btn btn-notion" data-bs-dismiss="modal">
                <?= lang('GetReady.close') ?>
            </button>
            <button type="button" class="btn btn-notion" onclick="editVehicle(<?= $vehicle['id'] ?>)">
                <i data-feather="edit" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                <?= lang('GetReady.edit') ?>
            </button>
        </div>
    </div>
</div>

<style>
/* Notion-style Step Icons */
.step-primary { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.step-info { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }
.step-warning { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.step-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
.step-success { background: rgba(34, 197, 94, 0.15); color: #22c55e; }

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.priority-normal { background: rgba(107, 114, 128, 0.15); color: #6b7280; }
.priority-urgent { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.priority-high { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
.priority-low { background: rgba(156, 163, 175, 0.15); color: #9ca3af; }

/* Compact buttons */
.btn-notion {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    color: #37352f;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.1s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-notion:hover {
    background: rgba(55, 53, 47, 0.05);
    border-color: rgba(55, 53, 47, 0.3);
    color: #37352f;
    text-decoration: none;
}

.btn-notion-primary {
    background: #2383e2;
    border-color: #2383e2;
    color: #ffffff;
}

.btn-notion-primary:hover {
    background: #1a73d1;
    border-color: #1a73d1;
    color: #ffffff;
}

/* Photo grid hover effects */
.photo-grid .photo-item:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Timer pulse animation */
.timer-display {
    position: relative;
}

.timer-display::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    opacity: 0;
    animation: timerPulse 3s infinite;
}

@keyframes timerPulse {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .modal-body .row {
        flex-direction: column;
    }
    
    .timer-section .row {
        flex-direction: column;
        text-align: center;
    }
    
    .timer-section .col-md-6 {
        margin-bottom: 12px;
    }
    
    .photo-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
    }
}
</style>

<script>
// Real-time timer updates for this vehicle
let vehicleTimerInterval;
let currentVehicleId = <?= $vehicle['id'] ?>;
let timerStartTime = Date.now();

// Start timer
function startVehicleTimer() {
    vehicleTimerInterval = setInterval(updateVehicleTimer, 1000);
}

// Update timer display
function updateVehicleTimer() {
    const timerElement = document.getElementById('vehicleTimer');
    if (!timerElement) return;
    
    // Get current time from server or calculate based on last known time
    // This is a simplified version - in production you'd want to sync with server
    const elapsed = Math.floor((Date.now() - timerStartTime) / 1000);
    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    
    timerElement.textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Pause timer
async function pauseTimer(vehicleId) {
    try {
        const response = await fetch(`<?= base_url('api/get-ready/time-tracking/pause/') ?>${vehicleId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            clearInterval(vehicleTimerInterval);
            location.reload(); // Reload to show updated state
        }
    } catch (error) {
        console.error('Error pausing timer:', error);
    }
}

// Resume timer
async function resumeTimer(vehicleId) {
    try {
        const response = await fetch(`<?= base_url('api/get-ready/time-tracking/resume/') ?>${vehicleId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            timerStartTime = Date.now();
            startVehicleTimer();
            location.reload(); // Reload to show updated state
        }
    } catch (error) {
        console.error('Error resuming timer:', error);
    }
}

// Initialize timer when modal opens
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!isset($vehicle['current_status']['is_paused']) || !$vehicle['current_status']['is_paused']): ?>
    startVehicleTimer();
    <?php endif; ?>
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Clean up timer when modal closes
document.getElementById('vehicleModal').addEventListener('hidden.bs.modal', function() {
    if (vehicleTimerInterval) {
        clearInterval(vehicleTimerInterval);
    }
});

// Photo modal functions
function openPhotoModal(photoUrl, index) {
    // TODO: Implement photo lightbox
    window.open(photoUrl, '_blank');
}

function showAllPhotos() {
    // TODO: Implement photo gallery modal
    console.log('Show all photos');
}

// Action functions
function moveToNextStep(vehicleId) {
    // TODO: Implement move to next step
    console.log('Move to next step:', vehicleId);
}

function updateLocation(vehicleId) {
    // TODO: Implement location update
    console.log('Update location:', vehicleId);
}

function uploadPhotos(vehicleId) {
    // TODO: Implement photo upload
    console.log('Upload photos:', vehicleId);
}

function printGetReadySheet(vehicleId) {
    window.open(`<?= base_url('get-ready/print/') ?>${vehicleId}`, '_blank');
}

function editVehicle(vehicleId) {
    // TODO: Implement edit vehicle
    console.log('Edit vehicle:', vehicleId);
}
</script>