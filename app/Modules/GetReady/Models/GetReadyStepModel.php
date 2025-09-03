<?php

namespace Modules\GetReady\Models;

use CodeIgniter\Model;

class GetReadyStepModel extends Model
{
    protected $table = 'get_ready_steps';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name', 'slug', 'description', 'order_position', 'color', 'icon',
        'is_service_step', 'requires_approval', 'auto_move_minutes',
        'notification_users', 'is_active', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'slug' => 'required|max_length[50]|is_unique[get_ready_steps.slug,id,{id}]|alpha_dash',
        'order_position' => 'required|integer|greater_than[0]',
        'color' => 'permit_empty|max_length[20]',
        'icon' => 'permit_empty|max_length[50]',
        'is_service_step' => 'permit_empty|in_list[0,1]',
        'requires_approval' => 'permit_empty|in_list[0,1]',
        'auto_move_minutes' => 'permit_empty|integer|greater_than[0]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Step name is required',
        ],
        'slug' => [
            'required' => 'Step slug is required',
            'is_unique' => 'This step slug already exists',
            'alpha_dash' => 'Slug can only contain letters, numbers, dashes and underscores',
        ],
        'order_position' => [
            'required' => 'Order position is required',
            'integer' => 'Order position must be a number',
        ],
    ];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    /**
     * Get all active steps in order
     */
    public function getActiveSteps()
    {
        return $this->where('is_active', 1)
                   ->orderBy('order_position', 'ASC')
                   ->findAll();
    }

    /**
     * Get step by slug
     */
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Get next step in workflow
     */
    public function getNextStep($currentStepId)
    {
        $currentStep = $this->find($currentStepId);
        if (!$currentStep) {
            return null;
        }

        return $this->where('order_position >', $currentStep['order_position'])
                   ->where('is_active', 1)
                   ->orderBy('order_position', 'ASC')
                   ->first();
    }

    /**
     * Get previous step in workflow
     */
    public function getPreviousStep($currentStepId)
    {
        $currentStep = $this->find($currentStepId);
        if (!$currentStep) {
            return null;
        }

        return $this->where('order_position <', $currentStep['order_position'])
                   ->where('is_active', 1)
                   ->orderBy('order_position', 'DESC')
                   ->first();
    }

    /**
     * Get steps that allow service assignments
     */
    public function getServiceSteps()
    {
        return $this->where('is_service_step', 1)
                   ->where('is_active', 1)
                   ->orderBy('order_position', 'ASC')
                   ->findAll();
    }

    /**
     * Get steps that require approval
     */
    public function getApprovalSteps()
    {
        return $this->where('requires_approval', 1)
                   ->where('is_active', 1)
                   ->orderBy('order_position', 'ASC')
                   ->findAll();
    }

    /**
     * Get steps with auto-move enabled
     */
    public function getAutoMoveSteps()
    {
        return $this->where('auto_move_minutes >', 0)
                   ->where('is_active', 1)
                   ->findAll();
    }

    /**
     * Reorder steps
     */
    public function reorderSteps($stepOrder)
    {
        $this->db->transStart();

        foreach ($stepOrder as $position => $stepId) {
            $this->update($stepId, ['order_position' => $position + 1]);
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }

    /**
     * Create default steps (used in installation/reset)
     */
    public function createDefaultSteps()
    {
        $defaultSteps = [
            [
                'name' => 'In Transit',
                'slug' => 'in_transit',
                'description' => 'Vehicle is being transported to the facility',
                'order_position' => 1,
                'color' => 'primary',
                'icon' => 'truck',
                'is_service_step' => 0,
                'notification_users' => json_encode([]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Detail',
                'slug' => 'in_detail',
                'description' => 'Vehicle is being detailed and cleaned',
                'order_position' => 2,
                'color' => 'info',
                'icon' => 'droplet',
                'is_service_step' => 0,
                'notification_users' => json_encode([]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Service',
                'slug' => 'in_service',
                'description' => 'Vehicle is being serviced by technicians',
                'order_position' => 3,
                'color' => 'warning',
                'icon' => 'tool',
                'is_service_step' => 1,
                'notification_users' => json_encode([]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Bodyshop',
                'slug' => 'in_bodyshop',
                'description' => 'Vehicle is in bodyshop for repairs',
                'order_position' => 4,
                'color' => 'danger',
                'icon' => 'settings',
                'is_service_step' => 1,
                'notification_users' => json_encode([]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Completed',
                'slug' => 'completed',
                'description' => 'Vehicle preparation completed',
                'order_position' => 5,
                'color' => 'success',
                'icon' => 'check-circle',
                'is_service_step' => 0,
                'notification_users' => json_encode([]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        return $this->insertBatch($defaultSteps);
    }

    /**
     * Get step statistics
     */
    public function getStepStatistics($stepId = null)
    {
        $orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        
        if ($stepId) {
            // Statistics for specific step
            $step = $this->find($stepId);
            if (!$step) {
                return null;
            }

            $vehicleCount = $orderModel->where('current_step_id', $stepId)
                                     ->where('status', 'active')
                                     ->countAllResults();

            // Average time in step
            $avgTime = $this->db->table('get_ready_time_tracking')
                               ->selectAvg('time_minutes')
                               ->where('step_id', $stepId)
                               ->where('exited_at IS NOT NULL')
                               ->get()
                               ->getRow()
                               ->time_minutes ?? 0;

            return [
                'step' => $step,
                'current_vehicles' => $vehicleCount,
                'average_time_minutes' => round($avgTime),
                'average_time_formatted' => $this->formatTime($avgTime)
            ];
        } else {
            // Statistics for all steps
            $steps = $this->getActiveSteps();
            $statistics = [];

            foreach ($steps as $step) {
                $vehicleCount = $orderModel->where('current_step_id', $step['id'])
                                         ->where('status', 'active')
                                         ->countAllResults();

                $avgTime = $this->db->table('get_ready_time_tracking')
                                   ->selectAvg('time_minutes')
                                   ->where('step_id', $step['id'])
                                   ->where('exited_at IS NOT NULL')
                                   ->get()
                                   ->getRow()
                                   ->time_minutes ?? 0;

                $statistics[] = [
                    'step' => $step,
                    'current_vehicles' => $vehicleCount,
                    'average_time_minutes' => round($avgTime),
                    'average_time_formatted' => $this->formatTime($avgTime)
                ];
            }

            return $statistics;
        }
    }

    /**
     * Get notification users for a step
     */
    public function getNotificationUsers($stepId)
    {
        $step = $this->find($stepId);
        if (!$step) {
            return [];
        }

        $userIds = json_decode($step['notification_users'] ?? '[]', true);
        if (empty($userIds)) {
            return [];
        }

        $userModel = model('App\Models\UserModel');
        return $userModel->whereIn('id', $userIds)->findAll();
    }

    /**
     * Add notification user to step
     */
    public function addNotificationUser($stepId, $userId)
    {
        $step = $this->find($stepId);
        if (!$step) {
            return false;
        }

        $currentUsers = json_decode($step['notification_users'] ?? '[]', true);
        
        if (!in_array($userId, $currentUsers)) {
            $currentUsers[] = $userId;
            return $this->update($stepId, [
                'notification_users' => json_encode($currentUsers)
            ]);
        }

        return true;
    }

    /**
     * Remove notification user from step
     */
    public function removeNotificationUser($stepId, $userId)
    {
        $step = $this->find($stepId);
        if (!$step) {
            return false;
        }

        $currentUsers = json_decode($step['notification_users'] ?? '[]', true);
        $updatedUsers = array_filter($currentUsers, function($id) use ($userId) {
            return $id != $userId;
        });

        return $this->update($stepId, [
            'notification_users' => json_encode(array_values($updatedUsers))
        ]);
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
        
        // Set default values
        if (!isset($data['data']['is_active'])) {
            $data['data']['is_active'] = 1;
        }
        
        if (!isset($data['data']['color'])) {
            $data['data']['color'] = 'primary';
        }
        
        if (!isset($data['data']['icon'])) {
            $data['data']['icon'] = 'box';
        }
        
        if (!isset($data['data']['notification_users'])) {
            $data['data']['notification_users'] = json_encode([]);
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
}