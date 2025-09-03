<?php

namespace Modules\GetReady\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class GetReadyOrderModel extends Model
{
    protected $table = 'get_ready_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'vin_number', 'stock_number', 'year', 'make', 'model', 'color', 'mileage',
        'client_id', 'contact_id', 'current_step_id', 'assigned_to', 'priority', 'status',
        'expected_completion', 'total_time_minutes', 'photos_count', 'photos_urls',
        'current_location', 'notes', 'internal_notes', 'short_url', 'short_url_slug',
        'lima_link_id', 'qr_generated_at', 'qr_url', 'nfc_token',
        'created_by', 'updated_by', 'deleted_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'vin_number' => 'required|max_length[50]|is_unique[get_ready_orders.vin_number,id,{id}]',
        'client_id' => 'required|integer',
        'current_step_id' => 'required|integer',
        'year' => 'permit_empty|integer|greater_than[1900]|less_than_equal_to[2030]',
        'mileage' => 'permit_empty|integer|greater_than_equal_to[0]',
        'priority' => 'permit_empty|in_list[normal,urgent,high,low]',
        'status' => 'permit_empty|in_list[active,completed,on_hold,cancelled]',
    ];

    protected $validationMessages = [
        'vin_number' => [
            'required' => 'VIN number is required',
            'is_unique' => 'This VIN number already exists',
        ],
        'client_id' => [
            'required' => 'Client is required',
        ],
        'current_step_id' => [
            'required' => 'Current step is required',
        ],
    ];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterInsert = ['afterInsert'];
    protected $afterUpdate = ['afterUpdate'];

    protected $stepModel;
    protected $timeModel;
    protected $activityModel;

    public function __construct(ConnectionInterface &$db = null)
    {
        parent::__construct($db);
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
        $this->activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
    }

    /**
     * Get vehicles by step with metrics
     */
    public function getVehiclesByStep($stepSlug, $withMetrics = true)
    {
        $step = $this->stepModel->where('slug', $stepSlug)->first();
        if (!$step) {
            return [];
        }

        $builder = $this->select([
            'get_ready_orders.*',
            'clients.name as client_name',
            'contacts.name as contact_name',
            'users.username as assigned_tech_name',
            'get_ready_time_tracking.entered_at',
            'get_ready_time_tracking.time_minutes as step_time_minutes',
            'DATEDIFF(NOW(), get_ready_time_tracking.entered_at) as days_in_step'
        ])
        ->join('clients', 'clients.id = get_ready_orders.client_id', 'left')
        ->join('contacts', 'contacts.id = get_ready_orders.contact_id', 'left')
        ->join('users', 'users.id = get_ready_orders.assigned_to', 'left')
        ->join('get_ready_time_tracking', 'get_ready_time_tracking.order_id = get_ready_orders.id AND get_ready_time_tracking.is_current = 1', 'left')
        ->where('get_ready_orders.current_step_id', $step['id'])
        ->where('get_ready_orders.status', 'active')
        ->orderBy('get_ready_time_tracking.entered_at', 'ASC');

        $vehicles = $builder->findAll();

        // Calculate total time for each vehicle
        foreach ($vehicles as &$vehicle) {
            $vehicle['total_time_formatted'] = $this->formatTime($vehicle['total_time_minutes']);
            $vehicle['step_time_formatted'] = $this->formatTime($vehicle['step_time_minutes'] ?? 0);
            $vehicle['photos_array'] = json_decode($vehicle['photos_urls'] ?? '[]', true);
        }

        return $vehicles;
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $stats = [];

        // Total vehicles in process
        $stats['total_active'] = $this->where('status', 'active')->countAllResults();

        // Vehicles by step
        $steps = $this->stepModel->where('is_active', 1)->orderBy('order_position')->findAll();
        $stats['by_step'] = [];

        foreach ($steps as $step) {
            $count = $this->where('current_step_id', $step['id'])
                         ->where('status', 'active')
                         ->countAllResults();
            
            $stats['by_step'][$step['slug']] = [
                'name' => $step['name'],
                'count' => $count,
                'color' => $step['color'],
                'icon' => $step['icon']
            ];
        }

        // Completed today
        $stats['completed_today'] = $this->where('status', 'completed')
                                       ->where('DATE(updated_at)', date('Y-m-d'))
                                       ->countAllResults();

        // Overdue vehicles (in step > 5 days)
        $stats['overdue'] = $this->join('get_ready_time_tracking', 'get_ready_time_tracking.order_id = get_ready_orders.id AND get_ready_time_tracking.is_current = 1')
                               ->where('get_ready_orders.status', 'active')
                               ->where('DATEDIFF(NOW(), get_ready_time_tracking.entered_at) >', 5)
                               ->countAllResults();

        return $stats;
    }

    /**
     * Get step metrics for dashboard cards
     */
    public function getStepMetrics($stepSlug)
    {
        $step = $this->stepModel->where('slug', $stepSlug)->first();
        if (!$step) {
            return null;
        }

        // Current vehicles in step
        $currentVehicles = $this->where('current_step_id', $step['id'])
                               ->where('status', 'active')
                               ->countAllResults();

        // Average time in step
        $avgTime = $this->db->table('get_ready_time_tracking')
                           ->selectAvg('time_minutes')
                           ->where('step_id', $step['id'])
                           ->where('exited_at IS NOT NULL')
                           ->get()
                           ->getRow()
                           ->time_minutes ?? 0;

        // Longest current wait
        $longestWait = $this->select('get_ready_orders.vin_number, DATEDIFF(NOW(), get_ready_time_tracking.entered_at) as days')
                           ->join('get_ready_time_tracking', 'get_ready_time_tracking.order_id = get_ready_orders.id AND get_ready_time_tracking.is_current = 1')
                           ->where('get_ready_orders.current_step_id', $step['id'])
                           ->where('get_ready_orders.status', 'active')
                           ->orderBy('days', 'DESC')
                           ->first();

        // Today's arrivals
        $todayArrivals = $this->join('get_ready_time_tracking', 'get_ready_time_tracking.order_id = get_ready_orders.id AND get_ready_time_tracking.is_current = 1')
                             ->where('get_ready_orders.current_step_id', $step['id'])
                             ->where('DATE(get_ready_time_tracking.entered_at)', date('Y-m-d'))
                             ->countAllResults();

        return [
            'total_vehicles' => $currentVehicles,
            'average_time_minutes' => round($avgTime),
            'average_time_formatted' => $this->formatTime($avgTime),
            'longest_wait' => $longestWait ? [
                'vin' => $longestWait['vin_number'],
                'days' => $longestWait['days']
            ] : null,
            'today_arrivals' => $todayArrivals,
            'step_info' => $step
        ];
    }

    /**
     * Move vehicle to next step
     */
    public function moveToStep($orderId, $toStepId, $userId = null)
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        $fromStepId = $order['current_step_id'];
        $toStep = $this->stepModel->find($toStepId);
        if (!$toStep) {
            return false;
        }

        $this->db->transStart();

        // Update order current step
        $updateData = [
            'current_step_id' => $toStepId,
            'updated_by' => $userId ?? auth()->id()
        ];

        // If moving to completed step, update status
        if ($toStep['slug'] === 'completed') {
            $updateData['status'] = 'completed';
        }

        $this->update($orderId, $updateData);

        // End current time tracking
        $this->timeModel->endCurrentStep($orderId, $fromStepId);

        // Start new time tracking
        $this->timeModel->startStep($orderId, $toStepId, $userId);

        // Log activity
        $this->activityModel->logActivity($orderId, 'moved_to_step', "Vehicle moved to {$toStep['name']}", $fromStepId, $toStepId, ['user_id' => $userId]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        }

        // Trigger event for notifications
        \CodeIgniter\Events\Events::trigger('get_ready_vehicle_moved', [
            'order_id' => $orderId,
            'from_step' => $fromStepId,
            'to_step' => $toStepId,
            'user_id' => $userId
        ]);

        return true;
    }

    /**
     * Assign technician to vehicle
     */
    public function assignTech($orderId, $techId, $userId = null)
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        $updateData = [
            'assigned_to' => $techId,
            'updated_by' => $userId ?? auth()->id()
        ];

        $updated = $this->update($orderId, $updateData);

        if ($updated) {
            // Log activity
            $this->activityModel->logActivity($orderId, 'assigned_tech', "Vehicle assigned to technician", null, null, [
                'tech_id' => $techId,
                'assigned_by' => $userId
            ]);

            // Trigger notification event
            \CodeIgniter\Events\Events::trigger('get_ready_tech_assigned', [
                'order_id' => $orderId,
                'tech_id' => $techId,
                'assigned_by' => $userId
            ]);
        }

        return $updated;
    }

    /**
     * Update photo count
     */
    public function updatePhotoCount($orderId, $photoCount)
    {
        return $this->update($orderId, ['photos_count' => $photoCount]);
    }

    /**
     * Generate NFC token
     */
    public function generateNFCToken($orderId)
    {
        $token = bin2hex(random_bytes(16));
        $this->update($orderId, ['nfc_token' => $token]);
        return $token;
    }

    /**
     * Find by NFC token
     */
    public function findByNFCToken($token)
    {
        return $this->where('nfc_token', $token)->first();
    }

    /**
     * Get full vehicle details with relationships
     */
    public function getVehicleDetails($orderId)
    {
        $vehicle = $this->select([
                'get_ready_orders.*',
                'clients.name as client_name',
                'contacts.name as contact_name',
                'contacts.phone as contact_phone',
                'contacts.email as contact_email',
                'users.username as assigned_tech_name',
                'get_ready_steps.name as current_step_name',
                'get_ready_steps.slug as current_step_slug',
                'get_ready_steps.color as current_step_color',
                'get_ready_steps.icon as current_step_icon'
            ])
            ->join('clients', 'clients.id = get_ready_orders.client_id', 'left')
            ->join('contacts', 'contacts.id = get_ready_orders.contact_id', 'left')
            ->join('users', 'users.id = get_ready_orders.assigned_to', 'left')
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_orders.current_step_id', 'left')
            ->find($orderId);

        if ($vehicle) {
            // Get time tracking history
            $vehicle['time_tracking'] = $this->timeModel->getTimeHistory($orderId);
            
            // Get activity log
            $vehicle['activities'] = $this->activityModel->getOrderActivities($orderId);
            
            // Parse photos
            $vehicle['photos_array'] = json_decode($vehicle['photos_urls'] ?? '[]', true);
            
            // Format times
            $vehicle['total_time_formatted'] = $this->formatTime($vehicle['total_time_minutes']);
        }

        return $vehicle;
    }

    /**
     * Format time in minutes to human readable
     */
    protected function formatTime($minutes)
    {
        if ($minutes < 60) {
            return $minutes . 'm';
        } elseif ($minutes < 1440) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $hours . 'h' . ($mins > 0 ? ' ' . $mins . 'm' : '');
        } else {
            $days = floor($minutes / 1440);
            $remainingHours = floor(($minutes % 1440) / 60);
            return $days . 'd' . ($remainingHours > 0 ? ' ' . $remainingHours . 'h' : '');
        }
    }

    /**
     * Before insert callback
     */
    protected function beforeInsert(array $data)
    {
        $data['data']['created_by'] = $data['data']['created_by'] ?? auth()->id();
        
        // Generate NFC token
        if (empty($data['data']['nfc_token'])) {
            $data['data']['nfc_token'] = bin2hex(random_bytes(16));
        }
        
        return $data;
    }

    /**
     * Before update callback
     */
    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_by'] = $data['data']['updated_by'] ?? auth()->id();
        return $data;
    }

    /**
     * After insert callback
     */
    protected function afterInsert(array $data)
    {
        $orderId = $data['id'];
        $orderData = $data['data'];

        // Start initial time tracking
        $this->timeModel->startStep($orderId, $orderData['current_step_id'], $orderData['created_by']);

        // Log creation activity
        $this->activityModel->logActivity($orderId, 'created', 'Get Ready order created', null, null, [
            'vin' => $orderData['vin_number'],
            'client_id' => $orderData['client_id']
        ]);

        return $data;
    }

    /**
     * After update callback
     */
    protected function afterUpdate(array $data)
    {
        // Update total time if needed
        if (isset($data['data']['current_step_id'])) {
            $this->updateTotalTime($data['id'][0]);
        }

        return $data;
    }

    /**
     * Update total accumulated time
     */
    protected function updateTotalTime($orderId)
    {
        $totalTime = $this->timeModel->getTotalTime($orderId);
        $this->update($orderId, ['total_time_minutes' => $totalTime], false);
    }
}