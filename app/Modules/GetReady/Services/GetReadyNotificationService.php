<?php

namespace Modules\GetReady\Services;

use App\Libraries\TwilioService;
use CodeIgniter\Config\BaseService;

class GetReadyNotificationService extends BaseService
{
    protected $twilioService;
    protected $orderModel;
    protected $stepModel;
    protected $userModel;

    public function __construct()
    {
        $this->twilioService = new TwilioService();
        $this->orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->userModel = model('App\Models\UserModel');
    }

    /**
     * Send notification when vehicle moves to new step
     */
    public function sendStepChangeNotification($orderId, $fromStepId, $toStepId)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return false;
        }

        $toStep = $this->stepModel->find($toStepId);
        if (!$toStep) {
            return false;
        }

        // Get users to notify for this step
        $notificationUsers = $this->stepModel->getNotificationUsers($toStepId);
        
        if (empty($notificationUsers)) {
            log_message('info', "No notification users configured for step: {$toStep['name']}");
            return true;
        }

        // Prepare message
        $vehicleInfo = trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']);
        $message = sprintf(
            lang('GetReady.vehicle_entered_step_notification'),
            $vehicleInfo,
            $order['vin_number'],
            $toStep['name'],
            $order['client_name']
        );

        // Add direct link if short URL exists
        if (!empty($order['short_url'])) {
            $message .= "\n\nView details: " . $order['short_url'];
        }

        $results = [];
        
        // Send SMS to each notification user
        foreach ($notificationUsers as $user) {
            if (empty($user['phone'])) {
                continue;
            }

            $metadata = [
                'module' => 'get_ready',
                'action' => 'step_change',
                'order_id' => $orderId,
                'step_id' => $toStepId,
                'user_id' => $user['id'],
                'vin' => $order['vin_number']
            ];

            $result = $this->twilioService->sendSMS($user['phone'], $message, $metadata);
            $results[] = [
                'user_id' => $user['id'],
                'phone' => $user['phone'],
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? 'Unknown error'
            ];

            // Log notification attempt
            log_message('info', "Step change notification sent to {$user['username']} ({$user['phone']}): " . ($result['success'] ? 'Success' : 'Failed'));
        }

        return $results;
    }

    /**
     * Send notification when tech is assigned
     */
    public function sendTechAssignmentNotification($orderId, $techId, $assignedBy = null)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        $tech = $this->userModel->find($techId);
        
        if (!$order || !$tech || empty($tech['phone'])) {
            return false;
        }

        $vehicleInfo = trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']);
        $currentStep = $order['current_step_name'];
        
        $message = sprintf(
            lang('GetReady.tech_assignment_notification'),
            $tech['username'],
            $vehicleInfo,
            $order['vin_number'],
            $currentStep,
            $order['client_name']
        );

        // Add location if available
        if (!empty($order['current_location'])) {
            $message .= "\nLocation: " . $order['current_location'];
        }

        // Add view link
        if (!empty($order['short_url'])) {
            $message .= "\n\nView details: " . $order['short_url'];
        }

        $metadata = [
            'module' => 'get_ready',
            'action' => 'tech_assigned',
            'order_id' => $orderId,
            'tech_id' => $techId,
            'assigned_by' => $assignedBy,
            'vin' => $order['vin_number']
        ];

        $result = $this->twilioService->sendSMS($tech['phone'], $message, $metadata);
        
        // Log assignment notification
        log_message('info', "Tech assignment notification sent to {$tech['username']} ({$tech['phone']}): " . ($result['success'] ? 'Success' : 'Failed'));

        return $result;
    }

    /**
     * Send overdue vehicle notification
     */
    public function sendOverdueVehicleNotification($orderId, $daysOverdue)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return false;
        }

        // Get managers and supervisors
        $managers = $this->userModel->whereIn('user_type', ['admin', 'manager'])->findAll();
        
        if (empty($managers)) {
            return false;
        }

        $vehicleInfo = trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']);
        $currentStep = $order['current_step_name'];
        
        $message = sprintf(
            lang('GetReady.overdue_vehicle_notification'),
            $vehicleInfo,
            $order['vin_number'],
            $daysOverdue,
            $currentStep,
            $order['client_name']
        );

        if (!empty($order['short_url'])) {
            $message .= "\n\nView details: " . $order['short_url'];
        }

        $metadata = [
            'module' => 'get_ready',
            'action' => 'overdue_alert',
            'order_id' => $orderId,
            'days_overdue' => $daysOverdue,
            'vin' => $order['vin_number']
        ];

        $results = [];
        
        foreach ($managers as $manager) {
            if (empty($manager['phone'])) {
                continue;
            }

            $result = $this->twilioService->sendSMS($manager['phone'], $message, $metadata);
            $results[] = [
                'user_id' => $manager['id'],
                'phone' => $manager['phone'],
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? 'Unknown error'
            ];
        }

        return $results;
    }

    /**
     * Send daily summary notification
     */
    public function sendDailySummaryNotification($recipientUserId = null)
    {
        $stats = $this->orderModel->getDashboardStats();
        
        // Build summary message
        $message = lang('GetReady.daily_summary_header') . "\n\n";
        $message .= sprintf(lang('GetReady.total_active_vehicles'), $stats['total_active']) . "\n";
        $message .= sprintf(lang('GetReady.completed_today'), $stats['completed_today']) . "\n";
        
        if ($stats['overdue'] > 0) {
            $message .= sprintf(lang('GetReady.overdue_vehicles'), $stats['overdue']) . "\n";
        }

        $message .= "\n" . lang('GetReady.by_step') . ":\n";
        foreach ($stats['by_step'] as $step) {
            if ($step['count'] > 0) {
                $message .= "• {$step['name']}: {$step['count']}\n";
            }
        }

        $message .= "\n" . lang('GetReady.generated_by_mda');

        $metadata = [
            'module' => 'get_ready',
            'action' => 'daily_summary',
            'date' => date('Y-m-d')
        ];

        // Send to specified user or all managers
        if ($recipientUserId) {
            $user = $this->userModel->find($recipientUserId);
            if ($user && !empty($user['phone'])) {
                return $this->twilioService->sendSMS($user['phone'], $message, $metadata);
            }
        } else {
            $managers = $this->userModel->whereIn('user_type', ['admin', 'manager'])->findAll();
            $results = [];
            
            foreach ($managers as $manager) {
                if (empty($manager['phone'])) {
                    continue;
                }

                $result = $this->twilioService->sendSMS($manager['phone'], $message, $metadata);
                $results[] = [
                    'user_id' => $manager['id'],
                    'phone' => $manager['phone'],
                    'success' => $result['success'] ?? false
                ];
            }
            
            return $results;
        }

        return false;
    }

    /**
     * Send completion notification
     */
    public function sendCompletionNotification($orderId)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return false;
        }

        // Get client contact
        $contact = null;
        if ($order['contact_id']) {
            $contactModel = model('App\Models\ContactModel');
            $contact = $contactModel->find($order['contact_id']);
        }

        if (!$contact || empty($contact['phone'])) {
            log_message('info', "No contact phone available for completion notification - Order ID: {$orderId}");
            return false;
        }

        $vehicleInfo = trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']);
        $totalTime = $order['total_time_formatted'];
        
        $message = sprintf(
            lang('GetReady.completion_notification'),
            $contact['name'],
            $vehicleInfo,
            $order['vin_number'],
            $totalTime,
            $order['client_name']
        );

        if (!empty($order['short_url'])) {
            $message .= "\n\nView final report: " . $order['short_url'];
        }

        $metadata = [
            'module' => 'get_ready',
            'action' => 'completed',
            'order_id' => $orderId,
            'client_id' => $order['client_id'],
            'contact_id' => $order['contact_id'],
            'vin' => $order['vin_number']
        ];

        $result = $this->twilioService->sendSMS($contact['phone'], $message, $metadata);
        
        log_message('info', "Completion notification sent to {$contact['name']} ({$contact['phone']}): " . ($result['success'] ? 'Success' : 'Failed'));

        return $result;
    }

    /**
     * Send emergency/urgent notification
     */
    public function sendEmergencyNotification($orderId, $urgencyLevel, $customMessage = null)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return false;
        }

        $vehicleInfo = trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']);
        
        $message = $customMessage ?: sprintf(
            lang('GetReady.emergency_notification'),
            strtoupper($urgencyLevel),
            $vehicleInfo,
            $order['vin_number'],
            $order['current_step_name']
        );

        if (!empty($order['short_url'])) {
            $message .= "\n\nView details: " . $order['short_url'];
        }

        $metadata = [
            'module' => 'get_ready',
            'action' => 'emergency_alert',
            'order_id' => $orderId,
            'urgency' => $urgencyLevel,
            'vin' => $order['vin_number']
        ];

        // Send to all managers immediately
        $managers = $this->userModel->whereIn('user_type', ['admin', 'manager'])->findAll();
        $results = [];
        
        foreach ($managers as $manager) {
            if (empty($manager['phone'])) {
                continue;
            }

            $result = $this->twilioService->sendSMS($manager['phone'], $message, $metadata);
            $results[] = [
                'user_id' => $manager['id'],
                'phone' => $manager['phone'],
                'success' => $result['success'] ?? false
            ];
        }

        // Also send to assigned tech if available
        if ($order['assigned_to']) {
            $tech = $this->userModel->find($order['assigned_to']);
            if ($tech && !empty($tech['phone'])) {
                $result = $this->twilioService->sendSMS($tech['phone'], $message, $metadata);
                $results[] = [
                    'user_id' => $tech['id'],
                    'phone' => $tech['phone'],
                    'success' => $result['success'] ?? false
                ];
            }
        }

        return $results;
    }

    /**
     * Test notification system
     */
    public function sendTestNotification($phone, $userId = null)
    {
        $message = lang('GetReady.test_notification_message');
        
        $metadata = [
            'module' => 'get_ready',
            'action' => 'test_notification',
            'user_id' => $userId,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->twilioService->sendSMS($phone, $message, $metadata);
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStatistics($days = 30)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));
        
        // Query SMS conversations for Get Ready notifications
        $smsModel = model('App\Models\SMSConversationModel');
        
        $stats = $smsModel->select([
                'metadata',
                'COUNT(*) as count',
                'SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered',
                'SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed'
            ])
            ->where('DATE(created_at) >=', $fromDate)
            ->where('direction', 'outbound')
            ->like('metadata', '"module":"get_ready"')
            ->get()
            ->getRowArray();

        // Get breakdown by action type
        $byAction = $smsModel->select('metadata')
                           ->where('DATE(created_at) >=', $fromDate)
                           ->where('direction', 'outbound')
                           ->like('metadata', '"module":"get_ready"')
                           ->findAll();

        $actionBreakdown = [];
        foreach ($byAction as $record) {
            $metadata = json_decode($record['metadata'] ?? '{}', true);
            $action = $metadata['action'] ?? 'unknown';
            $actionBreakdown[$action] = ($actionBreakdown[$action] ?? 0) + 1;
        }

        return [
            'total_sent' => $stats['count'] ?? 0,
            'delivered' => $stats['delivered'] ?? 0,
            'failed' => $stats['failed'] ?? 0,
            'delivery_rate' => $stats['count'] > 0 ? round(($stats['delivered'] / $stats['count']) * 100, 1) : 0,
            'by_action' => $actionBreakdown
        ];
    }
}