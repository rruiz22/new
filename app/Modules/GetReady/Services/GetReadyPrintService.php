<?php

namespace Modules\GetReady\Services;

use CodeIgniter\Config\BaseService;

class GetReadyPrintService extends BaseService
{
    protected $orderModel;
    protected $stepModel;
    protected $timeModel;
    protected $activityModel;

    public function __construct()
    {
        $this->orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
        $this->activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
    }

    /**
     * Generate Get Ready Sheet HTML for printing
     */
    public function generateGetReadySheet($orderId)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return null;
        }

        $timeTracking = $this->timeModel->getTimeHistory($orderId);
        $activities = $this->activityModel->getOrderActivities($orderId, 20);
        $checklistItems = $this->getChecklistItems();

        $data = [
            'order' => $order,
            'time_tracking' => $timeTracking,
            'activities' => $activities,
            'checklist_items' => $checklistItems,
            'generation_date' => date('F j, Y g:i A'),
            'qr_url' => $this->generateQRCode($order),
        ];

        return view('Modules\GetReady\Views\templates\get_ready_sheet', $data);
    }

    /**
     * Generate PDF of Get Ready Sheet
     */
    public function generateGetReadySheetPDF($orderId, $download = false)
    {
        $html = $this->generateGetReadySheet($orderId);
        if (!$html) {
            return null;
        }

        $order = $this->orderModel->find($orderId);
        $filename = "get_ready_sheet_{$order['vin_number']}_" . date('Y-m-d_H-i-s') . '.pdf';

        // Use mPDF like other modules
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'tempDir' => WRITEPATH . 'uploads/tmp'
        ]);

        // Set footer
        $footerHtml = '<div style="text-align: center; font-size: 8pt; color: #666;">
            Generated on ' . date('F j, Y g:i A') . ' | My Detail Area - Get Ready System<br>
            This document was automatically generated from the MDA Get Ready platform.
        </div>';

        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->WriteHTML($html);

        if ($download) {
            $mpdf->Output($filename, 'D');
        } else {
            // Save to writable/uploads
            $uploadPath = WRITEPATH . 'uploads/get-ready-sheets/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $filePath = $uploadPath . $filename;
            $mpdf->Output($filePath, 'F');
            
            return [
                'filename' => $filename,
                'filepath' => $filePath,
                'url' => base_url('uploads/get-ready-sheets/' . $filename)
            ];
        }
    }

    /**
     * Generate progress report
     */
    public function generateProgressReport($orderId)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order) {
            return null;
        }

        $timeTracking = $this->timeModel->getTimeHistory($orderId);
        $currentStatus = $this->timeModel->getCurrentStepStatus($orderId);
        $allSteps = $this->stepModel->getActiveSteps();

        // Calculate progress percentage
        $currentStepPosition = 0;
        foreach ($allSteps as $step) {
            if ($step['id'] == $order['current_step_id']) {
                $currentStepPosition = $step['order_position'];
                break;
            }
        }

        $totalSteps = count($allSteps);
        $progressPercentage = $totalSteps > 0 ? round(($currentStepPosition / $totalSteps) * 100) : 0;

        $data = [
            'order' => $order,
            'current_status' => $currentStatus,
            'time_tracking' => $timeTracking,
            'all_steps' => $allSteps,
            'progress_percentage' => $progressPercentage,
            'generation_date' => date('F j, Y g:i A'),
        ];

        return view('Modules\GetReady\Views\templates\progress_report', $data);
    }

    /**
     * Generate completion certificate
     */
    public function generateCompletionCertificate($orderId)
    {
        $order = $this->orderModel->getVehicleDetails($orderId);
        if (!$order || $order['status'] !== 'completed') {
            return null;
        }

        $timeTracking = $this->timeModel->getTimeHistory($orderId);
        $totalTime = $this->timeModel->getTotalTime($orderId);
        
        // Get completion date (last activity)
        $completionActivity = $this->activityModel->where('order_id', $orderId)
                                                 ->where('action', 'completed')
                                                 ->orderBy('created_at', 'DESC')
                                                 ->first();

        $data = [
            'order' => $order,
            'time_tracking' => $timeTracking,
            'total_time' => $this->formatTime($totalTime),
            'completion_date' => $completionActivity ? date('F j, Y', strtotime($completionActivity['created_at'])) : date('F j, Y'),
            'generation_date' => date('F j, Y g:i A'),
            'certificate_number' => 'GR-' . $order['id'] . '-' . date('Y'),
        ];

        return view('Modules\GetReady\Views\templates\completion_certificate', $data);
    }

    /**
     * Get 172-point checklist items organized by category
     */
    protected function getChecklistItems()
    {
        return [
            'Exterior Inspection' => [
                'Check paint condition and color match',
                'Inspect for dents, scratches, and damage',
                'Verify panel alignment and gaps',
                'Check headlight condition and alignment',
                'Inspect taillight condition and operation',
                'Verify turn signal operation',
                'Check windshield for chips or cracks',
                'Inspect side windows for damage',
                'Check rear window/windshield condition',
                'Verify wiper blade condition',
                'Inspect mirrors for damage and adjustment',
                'Check door handle operation',
                'Verify lock mechanism function',
                'Inspect weather stripping condition',
                'Check tire condition and tread depth',
                'Verify tire pressure (all 4 + spare)',
                'Inspect wheel condition and alignment',
                'Check for wheel damage or curb rash',
                'Verify lug nut tightness',
                'Inspect brake rotor condition (visible)',
                'Check exhaust system visible components',
                'Inspect undercarriage for visible damage',
                'Verify license plate mounting',
                'Check antenna condition and operation',
                'Inspect sunroof condition (if equipped)',
                'Verify convertible top operation (if equipped)',
                'Check trim pieces and molding',
                'Inspect grille condition',
                'Verify emblem/badge condition',
                'Check bumper alignment and condition'
            ],
            'Interior Inspection' => [
                'Check seat condition and operation',
                'Verify seat belt operation and condition',
                'Inspect dashboard condition',
                'Check instrument cluster operation',
                'Verify all warning lights function',
                'Test horn operation',
                'Check steering wheel condition',
                'Verify steering wheel controls',
                'Inspect shift knob/selector',
                'Check parking brake operation',
                'Verify pedal condition and operation',
                'Inspect floor mats condition',
                'Check carpet condition',
                'Verify interior lighting operation',
                'Test dome light operation',
                'Check map light function',
                'Verify glove box operation',
                'Inspect center console condition',
                'Check cup holder condition',
                'Verify armrest operation',
                'Inspect door panels condition',
                'Check window operation (all)',
                'Verify door lock operation',
                'Test interior door handles',
                'Check air vent operation',
                'Inspect headliner condition',
                'Verify sun visor operation',
                'Check vanity mirror condition',
                'Inspect trunk/cargo area',
                'Verify spare tire and tools presence'
            ],
            'Mechanical Systems' => [
                'Check engine oil level and condition',
                'Verify coolant level and condition',
                'Inspect brake fluid level',
                'Check power steering fluid',
                'Verify transmission fluid (if accessible)',
                'Inspect windshield washer fluid',
                'Check battery condition and terminals',
                'Verify belt condition and tension',
                'Inspect hose condition (visible)',
                'Check air filter condition',
                'Verify spark plug wires (if visible)',
                'Inspect engine bay for leaks',
                'Check exhaust system condition',
                'Verify suspension component condition',
                'Inspect brake system components',
                'Check CV joints and boots',
                'Verify differential fluid level',
                'Inspect fuel system components',
                'Check emissions system components',
                'Verify engine mount condition'
            ],
            'Electrical Systems' => [
                'Test headlight operation (high/low)',
                'Verify taillight operation',
                'Check brake light operation',
                'Test turn signal operation',
                'Verify hazard light function',
                'Check interior lighting',
                'Test dashboard illumination',
                'Verify radio/audio system',
                'Check power outlet operation',
                'Test air conditioning system',
                'Verify heater operation',
                'Check defrost system operation',
                'Test power window operation',
                'Verify power lock operation',
                'Check power seat operation (if equipped)',
                'Test power mirror operation',
                'Verify cruise control function',
                'Check alarm system operation',
                'Test remote key fob function',
                'Verify charging system operation'
            ],
            'Safety Systems' => [
                'Check airbag warning light',
                'Verify ABS system operation',
                'Test stability control system',
                'Check traction control operation',
                'Verify parking sensors (if equipped)',
                'Test backup camera (if equipped)',
                'Check blind spot monitoring (if equipped)',
                'Verify lane departure warning (if equipped)',
                'Test collision avoidance system (if equipped)',
                'Check tire pressure monitoring system',
                'Verify emergency brake assist',
                'Test hill start assist (if equipped)',
                'Check rollover protection (if equipped)',
                'Verify child safety lock operation',
                'Test emergency trunk release',
                'Check fire extinguisher (if equipped)',
                'Verify first aid kit (if equipped)',
                'Test emergency flasher operation',
                'Check reflective triangle presence',
                'Verify jack and tools presence'
            ],
            'Technology Systems' => [
                'Test navigation system operation',
                'Verify Bluetooth connectivity',
                'Check USB port operation',
                'Test auxiliary input function',
                'Verify hands-free phone operation',
                'Check voice command system',
                'Test satellite radio (if equipped)',
                'Verify DVD/entertainment system',
                'Check rear seat entertainment',
                'Test Wi-Fi hotspot (if equipped)',
                'Verify smartphone integration',
                'Check app connectivity',
                'Test remote start system',
                'Verify telematics system',
                'Check over-the-air update capability'
            ],
            'Final Quality Check' => [
                'Overall vehicle cleanliness',
                'Interior detail quality',
                'Exterior detail quality',
                'Engine bay cleanliness',
                'Trunk/cargo area cleanliness',
                'Wheel and tire cleanliness',
                'Glass cleaning quality',
                'Chrome and trim polish',
                'Leather/vinyl conditioning',
                'Carpet and fabric cleaning',
                'Odor elimination verification',
                'Air freshener application',
                'Final walk-around inspection',
                'Photo documentation completion',
                'Customer delivery preparation',
                'Keys and documentation ready',
                'Delivery checklist completed'
            ]
        ];
    }

    /**
     * Generate QR code for vehicle tracking
     */
    protected function generateQRCode($order)
    {
        if (empty($order['short_url'])) {
            return null;
        }

        // Use a QR code service or library to generate QR code
        // For now, return a placeholder QR code URL
        $qrData = urlencode($order['short_url']);
        return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$qrData}";
    }

    /**
     * Format time in minutes to human readable
     */
    protected function formatTime($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' minutes';
        } elseif ($minutes < 1440) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ($mins > 0 ? ' ' . $mins . ' min' : '');
        } else {
            $days = floor($minutes / 1440);
            $remainingHours = floor(($minutes % 1440) / 60);
            return $days . ' day' . ($days > 1 ? 's' : '') . ($remainingHours > 0 ? ' ' . $remainingHours . ' hour' . ($remainingHours > 1 ? 's' : '') : '');
        }
    }

    /**
     * Generate batch report for multiple vehicles
     */
    public function generateBatchReport($orderIds, $reportType = 'progress')
    {
        $orders = [];
        foreach ($orderIds as $orderId) {
            $order = $this->orderModel->getVehicleDetails($orderId);
            if ($order) {
                $order['time_tracking'] = $this->timeModel->getTimeHistory($orderId);
                $order['current_status'] = $this->timeModel->getCurrentStepStatus($orderId);
                $orders[] = $order;
            }
        }

        if (empty($orders)) {
            return null;
        }

        $data = [
            'orders' => $orders,
            'report_type' => $reportType,
            'generation_date' => date('F j, Y g:i A'),
            'total_vehicles' => count($orders),
        ];

        return view('Modules\GetReady\Views\templates\batch_report', $data);
    }

    /**
     * Generate analytics report
     */
    public function generateAnalyticsReport($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?: date('Y-m-d', strtotime('-30 days'));
        $dateTo = $dateTo ?: date('Y-m-d');

        // Get orders in date range
        $orders = $this->orderModel->where('DATE(created_at) >=', $dateFrom)
                                 ->where('DATE(created_at) <=', $dateTo)
                                 ->findAll();

        // Calculate statistics
        $totalOrders = count($orders);
        $completedOrders = array_filter($orders, function($order) {
            return $order['status'] === 'completed';
        });

        $avgCompletionTime = 0;
        if (!empty($completedOrders)) {
            $totalTime = array_sum(array_column($completedOrders, 'total_time_minutes'));
            $avgCompletionTime = $totalTime / count($completedOrders);
        }

        // Step statistics
        $stepStats = $this->stepModel->getStepStatistics();

        // Activity statistics
        $activityStats = $this->activityModel->getActivityStatistics(30);

        $data = [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'total_orders' => $totalOrders,
            'completed_orders' => count($completedOrders),
            'completion_rate' => $totalOrders > 0 ? round((count($completedOrders) / $totalOrders) * 100, 1) : 0,
            'avg_completion_time' => $this->formatTime($avgCompletionTime),
            'step_statistics' => $stepStats,
            'activity_statistics' => $activityStats,
            'generation_date' => date('F j, Y g:i A'),
        ];

        return view('Modules\GetReady\Views\templates\analytics_report', $data);
    }
}