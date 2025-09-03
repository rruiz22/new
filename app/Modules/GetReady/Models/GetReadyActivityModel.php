<?php

namespace Modules\GetReady\Models;

use CodeIgniter\Model;

class GetReadyActivityModel extends Model
{
    protected $table = 'get_ready_activities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'order_id', 'user_id', 'action', 'description', 'from_step_id', 'to_step_id',
        'metadata', 'ip_address', 'user_agent'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false; // Activities are not updated

    protected $validationRules = [
        'order_id' => 'required|integer',
        'action' => 'required|max_length[100]',
        'description' => 'permit_empty|max_length[1000]',
        'user_id' => 'permit_empty|integer',
        'from_step_id' => 'permit_empty|integer',
        'to_step_id' => 'permit_empty|integer',
    ];

    protected $beforeInsert = ['beforeInsert'];

    /**
     * Activity types constants
     */
    const ACTION_CREATED = 'created';
    const ACTION_MOVED_TO_STEP = 'moved_to_step';
    const ACTION_ASSIGNED_TECH = 'assigned_tech';
    const ACTION_ADDED_PHOTOS = 'added_photos';
    const ACTION_UPDATED_LOCATION = 'updated_location';
    const ACTION_NFC_SCANNED = 'nfc_scanned';
    const ACTION_TIMER_PAUSED = 'timer_paused';
    const ACTION_TIMER_RESUMED = 'timer_resumed';
    const ACTION_NOTES_ADDED = 'notes_added';
    const ACTION_STATUS_CHANGED = 'status_changed';
    const ACTION_COMPLETED = 'completed';
    const ACTION_DELETED = 'deleted';

    /**
     * Log activity for an order
     */
    public function logActivity($orderId, $action, $description = null, $fromStepId = null, $toStepId = null, $metadata = null, $userId = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'from_step_id' => $fromStepId,
            'to_step_id' => $toStepId,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'ip_address' => $this->getClientIP(),
            'user_agent' => $this->request->getUserAgent()->getAgentString() ?? null,
        ];

        return $this->insert($data);
    }

    /**
     * Get activities for an order
     */
    public function getOrderActivities($orderId, $limit = null, $actions = null)
    {
        $builder = $this->select([
                'get_ready_activities.*',
                'users.username',
                'from_steps.name as from_step_name',
                'from_steps.slug as from_step_slug',
                'from_steps.color as from_step_color',
                'from_steps.icon as from_step_icon',
                'to_steps.name as to_step_name',
                'to_steps.slug as to_step_slug',
                'to_steps.color as to_step_color',
                'to_steps.icon as to_step_icon'
            ])
            ->join('users', 'users.id = get_ready_activities.user_id', 'left')
            ->join('get_ready_steps as from_steps', 'from_steps.id = get_ready_activities.from_step_id', 'left')
            ->join('get_ready_steps as to_steps', 'to_steps.id = get_ready_activities.to_step_id', 'left')
            ->where('get_ready_activities.order_id', $orderId)
            ->orderBy('get_ready_activities.created_at', 'DESC');

        if ($actions && is_array($actions)) {
            $builder->whereIn('get_ready_activities.action', $actions);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        $activities = $builder->findAll();

        // Process metadata and format timestamps
        foreach ($activities as &$activity) {
            $activity['metadata_array'] = json_decode($activity['metadata'] ?? '{}', true);
            $activity['time_ago'] = $this->timeAgo($activity['created_at']);
            $activity['formatted_time'] = date('M j, Y g:i A', strtotime($activity['created_at']));
            $activity['action_icon'] = $this->getActionIcon($activity['action']);
            $activity['action_color'] = $this->getActionColor($activity['action']);
        }

        return $activities;
    }

    /**
     * Get recent activities across all orders
     */
    public function getRecentActivities($limit = 20, $clientId = null)
    {
        $builder = $this->select([
                'get_ready_activities.*',
                'get_ready_orders.vin_number',
                'get_ready_orders.year',
                'get_ready_orders.make',
                'get_ready_orders.model',
                'users.username',
                'clients.name as client_name',
                'from_steps.name as from_step_name',
                'from_steps.color as from_step_color',
                'to_steps.name as to_step_name',
                'to_steps.color as to_step_color'
            ])
            ->join('get_ready_orders', 'get_ready_orders.id = get_ready_activities.order_id')
            ->join('users', 'users.id = get_ready_activities.user_id', 'left')
            ->join('clients', 'clients.id = get_ready_orders.client_id', 'left')
            ->join('get_ready_steps as from_steps', 'from_steps.id = get_ready_activities.from_step_id', 'left')
            ->join('get_ready_steps as to_steps', 'to_steps.id = get_ready_activities.to_step_id', 'left')
            ->orderBy('get_ready_activities.created_at', 'DESC')
            ->limit($limit);

        if ($clientId) {
            $builder->where('get_ready_orders.client_id', $clientId);
        }

        $activities = $builder->findAll();

        foreach ($activities as &$activity) {
            $activity['metadata_array'] = json_decode($activity['metadata'] ?? '{}', true);
            $activity['time_ago'] = $this->timeAgo($activity['created_at']);
            $activity['vehicle_info'] = trim($activity['year'] . ' ' . $activity['make'] . ' ' . $activity['model']);
            $activity['action_icon'] = $this->getActionIcon($activity['action']);
            $activity['action_color'] = $this->getActionColor($activity['action']);
        }

        return $activities;
    }

    /**
     * Get activity statistics
     */
    public function getActivityStatistics($days = 30, $clientId = null)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));
        
        $builder = $this->select('action, COUNT(*) as count')
                       ->where('DATE(created_at) >=', $fromDate)
                       ->groupBy('action')
                       ->orderBy('count', 'DESC');

        if ($clientId) {
            $builder->join('get_ready_orders', 'get_ready_orders.id = get_ready_activities.order_id')
                   ->where('get_ready_orders.client_id', $clientId);
        }

        $actionStats = $builder->findAll();

        // Daily activity counts
        $builder = $this->select('DATE(created_at) as date, COUNT(*) as count')
                       ->where('DATE(created_at) >=', $fromDate)
                       ->groupBy('DATE(created_at)')
                       ->orderBy('date', 'ASC');

        if ($clientId) {
            $builder->join('get_ready_orders', 'get_ready_orders.id = get_ready_activities.order_id')
                   ->where('get_ready_orders.client_id', $clientId);
        }

        $dailyStats = $builder->findAll();

        return [
            'by_action' => $actionStats,
            'daily' => $dailyStats,
            'total' => array_sum(array_column($actionStats, 'count'))
        ];
    }

    /**
     * Get user activity summary
     */
    public function getUserActivitySummary($userId, $days = 30)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));
        
        $stats = $this->select('action, COUNT(*) as count')
                     ->where('user_id', $userId)
                     ->where('DATE(created_at) >=', $fromDate)
                     ->groupBy('action')
                     ->orderBy('count', 'DESC')
                     ->findAll();

        $total = array_sum(array_column($stats, 'count'));
        
        return [
            'total_activities' => $total,
            'by_action' => $stats,
            'most_common' => $stats[0] ?? null
        ];
    }

    /**
     * Clean old activities
     */
    public function cleanOldActivities($daysToKeep = 90)
    {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysToKeep} days"));
        return $this->where('DATE(created_at) <', $cutoffDate)->delete();
    }

    /**
     * Get action icon for display
     */
    protected function getActionIcon($action)
    {
        $icons = [
            self::ACTION_CREATED => 'plus-circle',
            self::ACTION_MOVED_TO_STEP => 'arrow-right',
            self::ACTION_ASSIGNED_TECH => 'user-check',
            self::ACTION_ADDED_PHOTOS => 'camera',
            self::ACTION_UPDATED_LOCATION => 'map-pin',
            self::ACTION_NFC_SCANNED => 'smartphone',
            self::ACTION_TIMER_PAUSED => 'pause-circle',
            self::ACTION_TIMER_RESUMED => 'play-circle',
            self::ACTION_NOTES_ADDED => 'edit',
            self::ACTION_STATUS_CHANGED => 'refresh-cw',
            self::ACTION_COMPLETED => 'check-circle',
            self::ACTION_DELETED => 'trash-2'
        ];

        return $icons[$action] ?? 'activity';
    }

    /**
     * Get action color for display
     */
    protected function getActionColor($action)
    {
        $colors = [
            self::ACTION_CREATED => 'success',
            self::ACTION_MOVED_TO_STEP => 'primary',
            self::ACTION_ASSIGNED_TECH => 'info',
            self::ACTION_ADDED_PHOTOS => 'warning',
            self::ACTION_UPDATED_LOCATION => 'secondary',
            self::ACTION_NFC_SCANNED => 'info',
            self::ACTION_TIMER_PAUSED => 'warning',
            self::ACTION_TIMER_RESUMED => 'success',
            self::ACTION_NOTES_ADDED => 'secondary',
            self::ACTION_STATUS_CHANGED => 'primary',
            self::ACTION_COMPLETED => 'success',
            self::ACTION_DELETED => 'danger'
        ];

        return $colors[$action] ?? 'secondary';
    }

    /**
     * Calculate time ago string
     */
    protected function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'just now';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . 'm ago';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . 'h ago';
        } elseif ($time < 2592000) {
            $days = floor($time / 86400);
            return $days . 'd ago';
        } else {
            return date('M j, Y', strtotime($datetime));
        }
    }

    /**
     * Get client IP address
     */
    protected function getClientIP()
    {
        $request = \Config\Services::request();
        
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->getHeader('CF-Connecting-IP')->getValue();
        } elseif ($request->hasHeader('X-Forwarded-For')) {
            return $request->getHeader('X-Forwarded-For')->getValue();
        } elseif ($request->hasHeader('X-Real-IP')) {
            return $request->getHeader('X-Real-IP')->getValue();
        } else {
            return $request->getIPAddress();
        }
    }

    /**
     * Before insert callback
     */
    protected function beforeInsert(array $data)
    {
        // Always set current timestamp
        $data['data']['created_at'] = date('Y-m-d H:i:s');

        // Set user if not provided and user is logged in
        if (!isset($data['data']['user_id']) && auth()->loggedIn()) {
            $data['data']['user_id'] = auth()->id();
        }

        return $data;
    }
}