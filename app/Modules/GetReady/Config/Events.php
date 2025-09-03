<?php

namespace Modules\GetReady\Config;

use CodeIgniter\Events\Events;

/**
 * Get Ready Module Events
 */

// Vehicle moved to new step event
Events::on('get_ready_vehicle_moved', function($data) {
    $notificationService = service('GetReadyNotificationService');
    $notificationService->sendStepChangeNotification($data['order_id'], $data['from_step'], $data['to_step']);
});

// Tech assigned event
Events::on('get_ready_tech_assigned', function($data) {
    $notificationService = service('GetReadyNotificationService');
    $notificationService->sendTechAssignmentNotification($data['order_id'], $data['tech_id']);
});

// Vehicle completed all steps event
Events::on('get_ready_completed', function($data) {
    // Log completion activity
    $activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
    $activityModel->logActivity($data['order_id'], 'completed', 'Vehicle completed all Get Ready steps');
});

// Photo uploaded event
Events::on('get_ready_photos_uploaded', function($data) {
    // Update photo count in main order
    $orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
    $orderModel->updatePhotoCount($data['order_id'], $data['photo_count']);
});

// NFC scan event
Events::on('get_ready_nfc_scanned', function($data) {
    // Log NFC scan activity
    $activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
    $activityModel->logActivity($data['order_id'], 'nfc_scanned', 'Vehicle location updated via NFC scan', null, null, [
        'location' => $data['location'],
        'scan_type' => 'nfc'
    ]);
});

// Timer paused/resumed events
Events::on('get_ready_timer_paused', function($data) {
    $timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
    $timeModel->pauseTimer($data['order_id'], $data['step_id']);
});

Events::on('get_ready_timer_resumed', function($data) {
    $timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
    $timeModel->resumeTimer($data['order_id'], $data['step_id']);
});

// Auto-move timer events
Events::on('get_ready_auto_move_triggered', function($data) {
    $controller = service('GetReadyController');
    $controller->autoMoveVehicle($data['order_id'], $data['from_step'], $data['to_step']);
});

// Integration with Sales Orders module
Events::on('sales_order_created', function($data) {
    // Automatically create Get Ready order if enabled
    $settings = service('Settings');
    if ($settings->get('get_ready.auto_create_from_sales')) {
        $getReadyService = service('GetReadyService');
        $getReadyService->createFromSalesOrder($data['sales_order_id']);
    }
});

// WebSocket events for real-time updates
Events::on('get_ready_realtime_update', function($data) {
    // Send WebSocket update to connected clients
    $webSocketService = service('WebSocketService');
    $webSocketService->broadcast('get_ready_update', $data);
});