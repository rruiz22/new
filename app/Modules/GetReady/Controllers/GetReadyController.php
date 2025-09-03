<?php

namespace Modules\GetReady\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class GetReadyController extends ResourceController
{
    protected $modelName = 'Modules\GetReady\Models\GetReadyOrderModel';
    protected $format = 'json';

    protected $orderModel;
    protected $stepModel;
    protected $timeModel;
    protected $activityModel;
    protected $notificationService;
    protected $printService;
    protected $nfcService;

    public function __construct()
    {
        $this->orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
        $this->activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
        $this->notificationService = service('GetReadyNotificationService');
        $this->printService = service('GetReadyPrintService');
        $this->nfcService = service('GetReadyNFCService');
    }

    /**
     * Main dashboard view
     */
    public function index()
    {
        $data = [
            'page_title' => lang('GetReady.get_ready_dashboard'),
            'steps' => $this->stepModel->getActiveSteps(),
            'stats' => $this->orderModel->getDashboardStats()
        ];

        return view('Modules\GetReady\Views\get_ready\index', $data);
    }

    /**
     * Dashboard content (AJAX)
     */
    public function dashboard_content()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $stats = $this->orderModel->getDashboardStats();
        $recentActivities = $this->activityModel->getRecentActivities(10);

        return $this->respond([
            'success' => true,
            'stats' => $stats,
            'recent_activities' => $recentActivities
        ]);
    }

    /**
     * Step view (In Transit, In Detail, etc.)
     */
    public function stepView($stepSlug)
    {
        $step = $this->stepModel->getBySlug($stepSlug);
        if (!$step) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'page_title' => $step['name'],
            'step' => $step,
            'steps' => $this->stepModel->getActiveSteps(),
            'metrics' => $this->orderModel->getStepMetrics($stepSlug)
        ];

        return view('Modules\GetReady\Views\get_ready\step_view', $data);
    }

    /**
     * Step content (AJAX)
     */
    public function stepContent($stepSlug)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $step = $this->stepModel->getBySlug($stepSlug);
        if (!$step) {
            return $this->failNotFound('Step not found');
        }

        $vehicles = $this->orderModel->getVehiclesByStep($stepSlug);
        $metrics = $this->orderModel->getStepMetrics($stepSlug);

        return $this->respond([
            'success' => true,
            'vehicles' => $vehicles,
            'metrics' => $metrics,
            'step' => $step
        ]);
    }

    /**
     * Individual step methods for direct access
     */
    public function inTransit()
    {
        return $this->stepView('in_transit');
    }

    public function inTransitContent()
    {
        return $this->stepContent('in_transit');
    }

    public function inDetail()
    {
        return $this->stepView('in_detail');
    }

    public function inDetailContent()
    {
        return $this->stepContent('in_detail');
    }

    public function inService()
    {
        return $this->stepView('in_service');
    }

    public function inServiceContent()
    {
        return $this->stepContent('in_service');
    }

    public function inBodyshop()
    {
        return $this->stepView('in_bodyshop');
    }

    public function inBodyshopContent()
    {
        return $this->stepContent('in_bodyshop');
    }

    /**
     * Service Manager interface
     */
    public function serviceManager()
    {
        // Get service steps
        $serviceSteps = $this->stepModel->getServiceSteps();
        
        // Get all technicians
        $userModel = model('App\Models\UserModel');
        $technicians = $userModel->where('user_type', 'staff')->findAll();

        // Get tech workload
        $techWorkload = [];
        foreach ($technicians as $tech) {
            $workload = $this->orderModel->where('assigned_to', $tech['id'])
                                        ->where('status', 'active')
                                        ->countAllResults();
            $techWorkload[$tech['id']] = $workload;
        }

        $data = [
            'page_title' => lang('GetReady.service_manager'),
            'service_steps' => $serviceSteps,
            'technicians' => $technicians,
            'tech_workload' => $techWorkload,
            'unassigned_vehicles' => $this->getUnassignedVehicles()
        ];

        return view('Modules\GetReady\Views\get_ready\service_manager', $data);
    }

    /**
     * Service Manager content (AJAX)
     */
    public function serviceManagerContent()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $serviceSteps = $this->stepModel->getServiceSteps();
        $unassignedVehicles = $this->getUnassignedVehicles();

        return $this->respond([
            'success' => true,
            'service_steps' => $serviceSteps,
            'unassigned_vehicles' => $unassignedVehicles
        ]);
    }

    /**
     * Create new Get Ready order modal
     */
    public function modal_form()
    {
        $clientModel = model('App\Models\ClientModel');
        $clients = $clientModel->where('status', 'active')->findAll();
        
        $steps = $this->stepModel->getActiveSteps();
        $initialStep = $steps[0] ?? null;

        $data = [
            'clients' => $clients,
            'steps' => $steps,
            'initial_step' => $initialStep
        ];

        return view('Modules\GetReady\Views\get_ready\modal_form', $data);
    }

    /**
     * Store new Get Ready order
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $rules = [
            'vin_number' => 'required|max_length[50]',
            'client_id' => 'required|integer',
            'year' => 'permit_empty|integer|greater_than[1900]',
            'make' => 'permit_empty|max_length[50]',
            'model' => 'permit_empty|max_length[100]',
            'current_step_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $data['created_by'] = auth()->id();
        
        // Set initial step if not provided
        if (empty($data['current_step_id'])) {
            $initialStep = $this->stepModel->where('order_position', 1)->first();
            $data['current_step_id'] = $initialStep['id'];
        }

        $orderId = $this->orderModel->insert($data);

        if ($orderId) {
            // Generate NFC token and short URL
            $this->nfcService->generateNFCData($orderId);

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.order_created_successfully'),
                'order_id' => $orderId,
                'redirect' => base_url("get-ready/view/{$orderId}")
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_create_order'));
        }
    }

    /**
     * View single vehicle details
     */
    public function view($orderId)
    {
        $vehicle = $this->orderModel->getVehicleDetails($orderId);
        if (!$vehicle) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'page_title' => "Get Ready: {$vehicle['vin_number']}",
            'vehicle' => $vehicle,
            'steps' => $this->stepModel->getActiveSteps(),
            'nfc_data' => $this->nfcService->generateNFCData($orderId)
        ];

        return view('Modules\GetReady\Views\get_ready\view', $data);
    }

    /**
     * Edit vehicle form
     */
    public function edit($id = null)
    {
        $vehicle = $this->orderModel->find($id);
        if (!$vehicle) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $clientModel = model('App\Models\ClientModel');
        $clients = $clientModel->where('status', 'active')->findAll();
        
        $steps = $this->stepModel->getActiveSteps();

        $data = [
            'page_title' => "Edit: {$vehicle['vin_number']}",
            'vehicle' => $vehicle,
            'clients' => $clients,
            'steps' => $steps
        ];

        return view('Modules\GetReady\Views\get_ready\edit', $data);
    }

    /**
     * Update vehicle
     */
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($id);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $rules = [
            'vin_number' => 'required|max_length[50]',
            'client_id' => 'required|integer',
            'year' => 'permit_empty|integer|greater_than[1900]',
            'make' => 'permit_empty|max_length[50]',
            'model' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $data['updated_by'] = auth()->id();

        $updated = $this->orderModel->update($id, $data);

        if ($updated) {
            // Log update activity
            $this->activityModel->logActivity($id, 'updated', 'Vehicle information updated');

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.vehicle_updated_successfully')
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_update_vehicle'));
        }
    }

    /**
     * Delete vehicle
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($id);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $deleted = $this->orderModel->delete($id);

        if ($deleted) {
            // Log deletion activity
            $this->activityModel->logActivity($id, 'deleted', 'Vehicle deleted from Get Ready system', null, null, null, auth()->id());

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.vehicle_deleted_successfully')
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_delete_vehicle'));
        }
    }

    /**
     * Move vehicle to next step
     */
    public function moveToStep($orderId, $toStepId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
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
            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.vehicle_moved_successfully', [$toStep['name']])
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_move_vehicle'));
        }
    }

    /**
     * Assign technician to vehicle
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
     * Update vehicle location
     */
    public function updateLocation($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $location = $this->request->getPost('location');
        if (empty($location)) {
            return $this->failValidationErrors(['location' => 'Location is required']);
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
     * Add photos to vehicle
     */
    public function addPhotos($orderId)
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $vehicle = $this->orderModel->find($orderId);
        if (!$vehicle) {
            return $this->failNotFound('Vehicle not found');
        }

        $photos = $this->request->getPost('photos');
        if (empty($photos) || !is_array($photos)) {
            return $this->failValidationErrors(['photos' => 'Photos are required']);
        }

        $existingPhotos = json_decode($vehicle['photos_urls'] ?? '[]', true);
        $allPhotos = array_merge($existingPhotos, $photos);

        $updated = $this->orderModel->update($orderId, [
            'photos_urls' => json_encode($allPhotos),
            'photos_count' => count($allPhotos),
            'updated_by' => auth()->id()
        ]);

        if ($updated) {
            // Log photo addition
            $photoCount = count($photos);
            $this->activityModel->logActivity($orderId, 'added_photos', "{$photoCount} photos added to vehicle", null, null, [
                'photo_count' => $photoCount,
                'total_photos' => count($allPhotos)
            ]);

            return $this->respond([
                'success' => true,
                'message' => lang('GetReady.photos_added_successfully', [$photoCount]),
                'total_photos' => count($allPhotos)
            ]);
        } else {
            return $this->fail(lang('GetReady.failed_to_add_photos'));
        }
    }

    /**
     * Print Get Ready sheet
     */
    public function print($orderId)
    {
        $html = $this->printService->generateGetReadySheet($orderId);
        if (!$html) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $html;
    }

    /**
     * Download Get Ready sheet PDF
     */
    public function downloadPdf($orderId)
    {
        $this->printService->generateGetReadySheetPDF($orderId, true);
    }

    /**
     * Print specialized Get Ready sheet
     */
    public function printGetReadySheet($orderId)
    {
        return $this->print($orderId);
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics()
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

    /**
     * Get dashboard stats (alias for compatibility)
     */
    public function dashboard_stats()
    {
        return $this->getStatistics();
    }

    /**
     * Get step metrics
     */
    public function stepMetrics($stepSlug)
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
     * Get performance data
     */
    public function performanceData()
    {
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('AJAX request required');
        }

        $days = $this->request->getGet('days') ?? 30;
        
        // Get step statistics
        $stepStats = $this->stepModel->getStepStatistics();
        
        // Get activity statistics
        $activityStats = $this->activityModel->getActivityStatistics($days);

        return $this->respond([
            'success' => true,
            'data' => [
                'step_statistics' => $stepStats,
                'activity_statistics' => $activityStats,
                'period_days' => $days
            ]
        ]);
    }

    /**
     * Get unassigned vehicles for Service Manager
     */
    protected function getUnassignedVehicles()
    {
        $serviceSteps = $this->stepModel->getServiceSteps();
        $serviceStepIds = array_column($serviceSteps, 'id');

        if (empty($serviceStepIds)) {
            return [];
        }

        return $this->orderModel->select([
                'get_ready_orders.*',
                'clients.name as client_name',
                'get_ready_steps.name as current_step_name',
                'get_ready_steps.color as current_step_color',
                'DATEDIFF(NOW(), get_ready_orders.created_at) as days_in_system'
            ])
            ->join('clients', 'clients.id = get_ready_orders.client_id', 'left')
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_orders.current_step_id', 'left')
            ->whereIn('get_ready_orders.current_step_id', $serviceStepIds)
            ->where('get_ready_orders.assigned_to IS NULL')
            ->where('get_ready_orders.status', 'active')
            ->orderBy('get_ready_orders.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Auto-move vehicle (called by scheduled tasks)
     */
    public function autoMoveVehicle($orderId, $fromStepId, $toStepId)
    {
        $moved = $this->orderModel->moveToStep($orderId, $toStepId, null);
        
        if ($moved) {
            $this->activityModel->logActivity($orderId, 'auto_moved', 'Vehicle automatically moved to next step', $fromStepId, $toStepId, [
                'auto_move' => true,
                'trigger' => 'scheduled_task'
            ]);
        }

        return $moved;
    }
}