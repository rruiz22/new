<?php

namespace Modules\GetReady\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class GetReadyApiController extends ResourceController
{
    protected $format = 'json';

    protected $orderModel;
    protected $stepModel;
    protected $timeModel;
    protected $activityModel;
    protected $notificationService;
    protected $nfcService;

    public function __construct()
    {
        $this->orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
        $this->activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
        $this->notificationService = service('GetReadyNotificationService');
        $this->nfcService = service('GetReadyNFCService');
    }

    /**
     * Get vehicles by step (for DataTables)
     */
    public function getVehiclesByStep($stepSlug)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $step = $this->stepModel->getBySlug($stepSlug);
        if (!$step) {
            return $this->failNotFound('Step not found');
        }

        // DataTables parameters
        $draw = $this->request->getGet('draw') ?? 1;
        $start = $this->request->getGet('start') ?? 0;
        $length = $this->request->getGet('length') ?? 10;
        $search = $this->request->getGet('search')['value'] ?? '';

        // Get vehicles for this step
        $vehicles = $this->orderModel->getVehiclesByStep($stepSlug);

        // Apply search filter if provided
        if (!empty($search)) {
            $vehicles = array_filter($vehicles, function($vehicle) use ($search) {
                return stripos($vehicle['vin_number'], $search) !== false ||
                       stripos($vehicle['make'], $search) !== false ||
                       stripos($vehicle['model'], $search) !== false ||
                       stripos($vehicle['client_name'], $search) !== false;
            });
        }

        $totalRecords = count($vehicles);
        $filteredRecords = count($vehicles);

        // Apply pagination
        $vehicles = array_slice($vehicles, $start, $length);

        // Format data for DataTables
        $data = [];
        foreach ($vehicles as $vehicle) {
            $vehicleInfo = "<a href=\"#\" onclick=\"openVehicleModal({$vehicle['id']})\">{$vehicle['vin_number']}</a><br>";
            $vehicleInfo .= "<small class=\"text-muted\">{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}</small>";

            $daysInStep = $vehicle['days_in_step'] ?? 0;
            $daysBadge = $daysInStep > 3 ? 'danger' : ($daysInStep > 1 ? 'warning' : 'success');

            $actions = "<div class=\"btn-group btn-group-sm\">";
            $actions .= "<button class=\"btn btn-primary\" onclick=\"openVehicleModal({$vehicle['id']})\" title=\"View Details\">";
            $actions .= "<i data-feather=\"eye\"></i></button>";
            $actions .= "<button class=\"btn btn-success\" onclick=\"moveToNextStep({$vehicle['id']})\" title=\"Move to Next Step\">";
            $actions .= "<i data-feather=\"arrow-right\"></i></button>";
            
            if ($step['is_service_step']) {
                $actions .= "<button class=\"btn btn-info\" onclick=\"assignTechModal({$vehicle['id']})\" title=\"Assign Tech\">";
                $actions .= "<i data-feather=\"user-plus\"></i></button>";
            }
            
            $actions .= "</div>";

            $data[] = [
                $vehicleInfo,
                $vehicle['client_name'],
                "<span class=\"badge bg-{$daysBadge}\">{$daysInStep} days</span>",
                $vehicle['total_time_formatted'],
                $vehicle['current_location'] ?: 'Not set',
                $actions
            ];
        }

        return $this->respond([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get single vehicle details
     */
    public function getVehicle($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->getVehicleDetails($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        return $this->respond([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Get vehicle modal HTML
     */
    public function getVehicleModal($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->getVehicleDetails($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $steps = $this->stepModel->getActiveSteps();
        $technicians = model('App\Models\UserModel')->where('user_type', 'staff')->findAll();

        $data = [
            'vehicle' => $vehicle,
            'steps' => $steps,
            'technicians' => $technicians
        ];

        $html = view('Modules\GetReady\Views\get_ready\vehicle_modal', $data);

        return $this->respond([
            'success' => true,
            'html' => $html,
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Get time tracking for vehicle
     */
    public function getTimeTracking($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $timeHistory = $this->timeModel->getTimeHistory($orderId);
        $currentStatus = $this->timeModel->getCurrentStepStatus($orderId);
        $totalTime = $this->timeModel->getTotalTime($orderId);

        return $this->respond([
            'success' => true,
            'time_history' => $timeHistory,
            'current_status' => $currentStatus,
            'total_time' => $totalTime
        ]);
    }

    /**
     * Pause timer
     */
    public function pauseTimer($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $result = $this->timeModel->pauseTimer($orderId, null, auth()->id());

        if ($result) {
            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.timer_paused_successfully')
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_pause_timer'));
        }
    }

    /**
     * Resume timer
     */
    public function resumeTimer($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $result = $this->timeModel->resumeTimer($orderId, null, auth()->id());

        if ($result) {
            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.timer_resumed_successfully')
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_resume_timer'));
        }
    }

    /**
     * Get all steps
     */
    public function getSteps()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $steps = $this->stepModel->getActiveSteps();

        return $this->respond([
            'success' => true,
            'steps' => $steps
        ]);
    }

    /**
     * Get step metrics
     */
    public function getStepMetrics($stepSlug)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $metrics = $this->orderModel->getStepMetrics($stepSlug);

        if ($metrics) {
            return $this->respond([
                'success' => true,
                'metrics' => $metrics
            ]);
        } else {
            return $this->failNotFound('Step not found');
        }
    }

    /**
     * Get available technicians
     */
    public function getAvailableTechs()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $userModel = model('App\Models\UserModel');
        $technicians = $userModel->where('user_type', 'staff')->findAll();

        // Get current workload for each tech
        foreach ($technicians as &$tech) {
            $tech['current_workload'] = $this->orderModel->where('assigned_to', $tech['id'])
                                                        ->where('status', 'active')
                                                        ->countAllResults();
        }

        return $this->respond([
            'success' => true,
            'technicians' => $technicians
        ]);
    }

    /**
     * Assign technician
     */
    public function assignTech($orderId, $techId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $userModel = model('App\Models\UserModel');
        $tech = $userModel->find($techId);
        if (!$tech) {
            return $this->failNotFound('Technician not found');
        }

        $assigned = $this->orderModel->assignTech($orderId, $techId, auth()->id());

        if ($assigned) {
            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.technician_assigned_successfully', [$tech['username']])
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_assign_technician'));
        }
    }

    /**
     * Get tech workload
     */
    public function getTechWorkload($techId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $workload = $this->orderModel->select([
                'get_ready_orders.*',
                'clients.name as client_name',
                'get_ready_steps.name as current_step_name',
                'get_ready_steps.color as current_step_color'
            ])
            ->join('clients', 'clients.id = get_ready_orders.client_id', 'left')
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_orders.current_step_id', 'left')
            ->where('get_ready_orders.assigned_to', $techId)
            ->where('get_ready_orders.status', 'active')
            ->findAll();

        $analytics = $this->timeModel->getTechPerformanceAnalytics($techId);

        return $this->respond([
            'success' => true,
            'workload' => $workload,
            'analytics' => $analytics
        ]);
    }

    /**
     * Move vehicle (mobile/NFC endpoint)
     */
    public function moveVehicle()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $orderId = $this->request->getPost('order_id');
        $toStepId = $this->request->getPost('to_step_id');
        $notes = $this->request->getPost('notes');

        if (!$orderId || !$toStepId) {
            return $this->failValidationErrors(['Missing order_id or to_step_id']);
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $toStep = $this->stepModel->find($toStepId);
        if (!$toStep) {
            return $this->failNotFound('Step not found');
        }

        $moved = $this->orderModel->moveToStep($orderId, $toStepId, auth()->id());

        if ($moved) {
            // Add notes if provided
            if ($notes) {
                $this->activityModel->logActivity($orderId, 'notes_added', "Notes added: {$notes}", null, null, [
                    'notes' => $notes,
                    'added_via' => 'api'
                ]);
            }

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.vehicle_moved_successfully', [$toStep['name']])
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_move_vehicle'));
        }
    }

    /**
     * NFC scan endpoint
     */
    public function scanNFC()
    {
        $nfcToken = $this->request->getPost('nfc_token');
        $locationData = $this->request->getPost('location_data');

        if (!$nfcToken) {
            return $this->failValidationErrors(['nfc_token' => 'NFC token is required']);
        }

        $result = $this->nfcService->processScan($nfcToken, $locationData, auth()->id());

        if ($result['success']) {
            return $this->respond($result);
        } else {
            return $this->fail($result['message'], 400, $result['code'] ?? 'SCAN_FAILED');
        }
    }

    /**
     * Update location
     */
    public function updateLocation()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $orderId = $this->request->getPost('order_id');
        $location = $this->request->getPost('location');

        if (!$orderId || !$location) {
            return $this->failValidationErrors(['Missing order_id or location']);
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $updated = $this->orderModel->update($orderId, [
            'current_location' => $location,
            'updated_by' => auth()->id()
        ]);

        if ($updated) {
            // Log location update
            $this->activityModel->logActivity($orderId, 'updated_location', "Location updated to: {$location}");

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.location_updated_successfully')
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_update_location'));
        }
    }

    /**
     * Upload photos
     */
    public function uploadPhotos($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        // Handle file upload using existing S3 upload functionality
        $files = $this->request->getFiles();
        if (empty($files)) {
            return $this->failValidationErrors(['files' => 'No files uploaded']);
        }

        $uploadedUrls = [];
        $uploadService = service('SecureFileUploadService'); // Use existing upload service

        foreach ($files as $file) {
            if ($file->isValid()) {
                $result = $uploadService->uploadFile($file, 'get-ready-photos');
                if ($result['success']) {
                    $uploadedUrls[] = $result['url'];
                }
            }
        }

        if (!empty($uploadedUrls)) {
            $existingPhotos = json_decode($vehicle['photos_urls'] ?? '[]', true);
            $allPhotos = array_merge($existingPhotos, $uploadedUrls);

            $updated = $this->orderModel->update($orderId, [
                'photos_urls' => json_encode($allPhotos),
                'photos_count' => count($allPhotos),
                'updated_by' => auth()->id()
            ]);

            if ($updated) {
                // Log photo upload
                $photoCount = count($uploadedUrls);
                $this->activityModel->logActivity($orderId, 'added_photos', "{$photoCount} photos uploaded", null, null, [
                    'photo_count' => $photoCount,
                    'total_photos' => count($allPhotos),
                    'uploaded_via' => 'api'
                ]);

                return $this->respond([
                    'success' => true,
                    'message' => lang('GetReady.photos_uploaded_successfully', [$photoCount]),
                    'uploaded_urls' => $uploadedUrls,
                    'total_photos' => count($allPhotos)
                ]);
            }
        }

        return $this->fail(lang('GetReady.failed_to_upload_photos'));
    }

    /**
     * Get photos for vehicle
     */
    public function getPhotos($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $photos = json_decode($vehicle['photos_urls'] ?? '[]', true);

        return $this->respond([
            'success' => true,
            'photos' => $photos,
            'count' => count($photos)
        ]);
    }

    /**
     * Delete photo
     */
    public function deletePhoto($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $photoUrl = $this->request->getPost('photo_url');
        if (!$photoUrl) {
            return $this->failValidationErrors(['photo_url' => 'Photo URL is required']);
        }

        $photos = json_decode($vehicle['photos_urls'] ?? '[]', true);
        $updatedPhotos = array_filter($photos, function($url) use ($photoUrl) {
            return $url !== $photoUrl;
        });

        $updated = $this->orderModel->update($orderId, [
            'photos_urls' => json_encode(array_values($updatedPhotos)),
            'photos_count' => count($updatedPhotos),
            'updated_by' => auth()->id()
        ]);

        if ($updated) {
            // Log photo deletion
            $this->activityModel->logActivity($orderId, 'deleted_photo', 'Photo deleted from vehicle');

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.photo_deleted_successfully'),
                'remaining_photos' => count($updatedPhotos)
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_delete_photo'));
        }
    }

    /**
     * Get dashboard data for real-time updates
     */
    public function getDashboardData()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $stats = $this->orderModel->getDashboardStats();
        $recentActivities = $this->activityModel->getRecentActivities(5);
        $steps = $this->stepModel->getActiveSteps();

        // Get quick metrics for each step
        $stepMetrics = [];
        foreach ($steps as $step) {
            $stepMetrics[$step['slug']] = $this->orderModel->getStepMetrics($step['slug']);
        }

        return $this->respond([
            'success' => true,
            'stats' => $stats,
            'recent_activities' => $recentActivities,
            'step_metrics' => $stepMetrics,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $limit = $this->request->getGet('limit') ?? 10;
        $recentActivities = $this->activityModel->getRecentActivities($limit);

        return $this->respond([
            'success' => true,
            'activities' => $recentActivities,
            'count' => count($recentActivities)
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $stats = $this->orderModel->getDashboardStats();

        return $this->respond([
            'success' => true,
            'stats' => $stats
        ]);
    }
}