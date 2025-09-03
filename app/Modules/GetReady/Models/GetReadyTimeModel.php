<?php

namespace Modules\GetReady\Models;

use CodeIgniter\Model;

class GetReadyTimeModel extends Model
{
    protected $table = 'get_ready_time_tracking';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'order_id', 'step_id', 'sub_step', 'assigned_to', 'entered_at', 'exited_at',
        'pause_start', 'pause_total_minutes', 'time_minutes', 'is_current', 'notes',
        'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id' => 'required|integer',
        'step_id' => 'required|integer',
        'entered_at' => 'required|valid_date',
        'assigned_to' => 'permit_empty|integer',
        'is_current' => 'permit_empty|in_list[0,1]',
        'time_minutes' => 'permit_empty|integer|greater_than_equal_to[0]',
        'pause_total_minutes' => 'permit_empty|integer|greater_than_equal_to[0]'
    ];

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    /**
     * Start time tracking for a step
     */
    public function startStep($orderId, $stepId, $userId = null, $subStep = null)
    {
        $data = [
            'order_id' => $orderId,
            'step_id' => $stepId,
            'sub_step' => $subStep,
            'assigned_to' => $userId,
            'entered_at' => date('Y-m-d H:i:s'),
            'is_current' => 1,
            'time_minutes' => 0,
            'pause_total_minutes' => 0,
            'created_by' => $userId ?? auth()->id()
        ];

        return $this->insert($data);
    }

    /**
     * End current step tracking
     */
    public function endCurrentStep($orderId, $stepId = null)
    {
        $builder = $this->where('order_id', $orderId)
                       ->where('is_current', 1);
        
        if ($stepId) {
            $builder->where('step_id', $stepId);
        }

        $currentTracking = $builder->findAll();

        foreach ($currentTracking as $tracking) {
            $this->endStep($tracking['id']);
        }

        return true;
    }

    /**
     * End specific step tracking
     */
    public function endStep($trackingId)
    {
        $tracking = $this->find($trackingId);
        if (!$tracking || $tracking['exited_at']) {
            return false;
        }

        $exitTime = date('Y-m-d H:i:s');
        $totalMinutes = $this->calculateActiveMinutes($tracking['entered_at'], $exitTime, $tracking['pause_total_minutes'], $tracking['pause_start']);

        $updateData = [
            'exited_at' => $exitTime,
            'is_current' => 0,
            'time_minutes' => $totalMinutes,
            'updated_by' => auth()->id()
        ];

        // If currently paused, end the pause
        if ($tracking['pause_start']) {
            $pauseTime = $this->calculateMinutesDiff($tracking['pause_start'], $exitTime);
            $updateData['pause_total_minutes'] = $tracking['pause_total_minutes'] + $pauseTime;
            $updateData['pause_start'] = null;
        }

        return $this->update($trackingId, $updateData);
    }

    /**
     * Pause timer for current step
     */
    public function pauseTimer($orderId, $stepId = null, $userId = null)
    {
        $builder = $this->where('order_id', $orderId)
                       ->where('is_current', 1)
                       ->where('pause_start IS NULL');
        
        if ($stepId) {
            $builder->where('step_id', $stepId);
        }

        $currentTracking = $builder->first();
        if (!$currentTracking) {
            return false;
        }

        $updateData = [
            'pause_start' => date('Y-m-d H:i:s'),
            'updated_by' => $userId ?? auth()->id()
        ];

        $result = $this->update($currentTracking['id'], $updateData);

        if ($result) {
            // Trigger event
            \CodeIgniter\Events\Events::trigger('get_ready_timer_paused', [
                'order_id' => $orderId,
                'step_id' => $currentTracking['step_id'],
                'user_id' => $userId
            ]);
        }

        return $result;
    }

    /**
     * Resume timer for current step
     */
    public function resumeTimer($orderId, $stepId = null, $userId = null)
    {
        $builder = $this->where('order_id', $orderId)
                       ->where('is_current', 1)
                       ->where('pause_start IS NOT NULL');
        
        if ($stepId) {
            $builder->where('step_id', $stepId);
        }

        $currentTracking = $builder->first();
        if (!$currentTracking) {
            return false;
        }

        $resumeTime = date('Y-m-d H:i:s');
        $pauseTime = $this->calculateMinutesDiff($currentTracking['pause_start'], $resumeTime);

        $updateData = [
            'pause_start' => null,
            'pause_total_minutes' => $currentTracking['pause_total_minutes'] + $pauseTime,
            'updated_by' => $userId ?? auth()->id()
        ];

        $result = $this->update($currentTracking['id'], $updateData);

        if ($result) {
            // Trigger event
            \CodeIgniter\Events\Events::trigger('get_ready_timer_resumed', [
                'order_id' => $orderId,
                'step_id' => $currentTracking['step_id'],
                'user_id' => $userId
            ]);
        }

        return $result;
    }

    /**
     * Get current step tracking status
     */
    public function getCurrentStepStatus($orderId)
    {
        $current = $this->select([
                'get_ready_time_tracking.*',
                'get_ready_steps.name as step_name',
                'get_ready_steps.slug as step_slug',
                'get_ready_steps.color as step_color',
                'users.username as assigned_tech_name'
            ])
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_time_tracking.step_id')
            ->join('users', 'users.id = get_ready_time_tracking.assigned_to', 'left')
            ->where('get_ready_time_tracking.order_id', $orderId)
            ->where('get_ready_time_tracking.is_current', 1)
            ->first();

        if ($current) {
            $current['is_paused'] = !is_null($current['pause_start']);
            $current['current_time_minutes'] = $this->calculateCurrentMinutes($current);
            $current['current_time_formatted'] = $this->formatTime($current['current_time_minutes']);
        }

        return $current;
    }

    /**
     * Get complete time history for an order
     */
    public function getTimeHistory($orderId)
    {
        $history = $this->select([
                'get_ready_time_tracking.*',
                'get_ready_steps.name as step_name',
                'get_ready_steps.slug as step_slug',
                'get_ready_steps.color as step_color',
                'get_ready_steps.icon as step_icon',
                'users.username as assigned_tech_name'
            ])
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_time_tracking.step_id')
            ->join('users', 'users.id = get_ready_time_tracking.assigned_to', 'left')
            ->where('get_ready_time_tracking.order_id', $orderId)
            ->orderBy('get_ready_time_tracking.entered_at', 'ASC')
            ->findAll();

        foreach ($history as &$record) {
            if ($record['is_current']) {
                $record['time_minutes'] = $this->calculateCurrentMinutes($record);
            }
            $record['time_formatted'] = $this->formatTime($record['time_minutes']);
            $record['is_paused'] = !is_null($record['pause_start']);
        }

        return $history;
    }

    /**
     * Get total accumulated time for an order
     */
    public function getTotalTime($orderId)
    {
        $history = $this->where('order_id', $orderId)->findAll();
        $totalMinutes = 0;

        foreach ($history as $record) {
            if ($record['is_current']) {
                $totalMinutes += $this->calculateCurrentMinutes($record);
            } else {
                $totalMinutes += $record['time_minutes'];
            }
        }

        return $totalMinutes;
    }

    /**
     * Get step performance analytics
     */
    public function getStepPerformanceAnalytics($stepId, $days = 30)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));

        // Average time in step
        $avgTime = $this->selectAvg('time_minutes')
                       ->where('step_id', $stepId)
                       ->where('exited_at IS NOT NULL')
                       ->where('DATE(entered_at) >=', $fromDate)
                       ->get()
                       ->getRow()
                       ->time_minutes ?? 0;

        // Total vehicles processed
        $totalProcessed = $this->where('step_id', $stepId)
                              ->where('exited_at IS NOT NULL')
                              ->where('DATE(entered_at) >=', $fromDate)
                              ->countAllResults();

        // Current vehicles in step
        $currentInStep = $this->where('step_id', $stepId)
                             ->where('is_current', 1)
                             ->countAllResults();

        // Daily breakdown
        $dailyData = $this->select('DATE(entered_at) as date, COUNT(*) as count, AVG(time_minutes) as avg_time')
                         ->where('step_id', $stepId)
                         ->where('DATE(entered_at) >=', $fromDate)
                         ->groupBy('DATE(entered_at)')
                         ->orderBy('date', 'ASC')
                         ->findAll();

        return [
            'average_time_minutes' => round($avgTime),
            'average_time_formatted' => $this->formatTime($avgTime),
            'total_processed' => $totalProcessed,
            'current_in_step' => $currentInStep,
            'daily_data' => $dailyData
        ];
    }

    /**
     * Get tech performance analytics
     */
    public function getTechPerformanceAnalytics($techId, $days = 30)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));

        // Average time per vehicle
        $avgTime = $this->selectAvg('time_minutes')
                       ->where('assigned_to', $techId)
                       ->where('exited_at IS NOT NULL')
                       ->where('DATE(entered_at) >=', $fromDate)
                       ->get()
                       ->getRow()
                       ->time_minutes ?? 0;

        // Total vehicles handled
        $totalHandled = $this->where('assigned_to', $techId)
                            ->where('exited_at IS NOT NULL')
                            ->where('DATE(entered_at) >=', $fromDate)
                            ->countAllResults();

        // Current workload
        $currentWorkload = $this->where('assigned_to', $techId)
                               ->where('is_current', 1)
                               ->countAllResults();

        // By step breakdown
        $byStep = $this->select([
                'get_ready_steps.name as step_name',
                'get_ready_steps.slug as step_slug',
                'COUNT(*) as count',
                'AVG(get_ready_time_tracking.time_minutes) as avg_time'
            ])
            ->join('get_ready_steps', 'get_ready_steps.id = get_ready_time_tracking.step_id')
            ->where('get_ready_time_tracking.assigned_to', $techId)
            ->where('DATE(get_ready_time_tracking.entered_at) >=', $fromDate)
            ->groupBy('get_ready_time_tracking.step_id')
            ->findAll();

        return [
            'average_time_minutes' => round($avgTime),
            'average_time_formatted' => $this->formatTime($avgTime),
            'total_handled' => $totalHandled,
            'current_workload' => $currentWorkload,
            'by_step' => $byStep
        ];
    }

    /**
     * Calculate current minutes for active tracking
     */
    protected function calculateCurrentMinutes($tracking)
    {
        if ($tracking['exited_at']) {
            return $tracking['time_minutes'];
        }

        $now = date('Y-m-d H:i:s');
        $pauseStart = $tracking['pause_start'];
        $pauseTotal = $tracking['pause_total_minutes'];

        return $this->calculateActiveMinutes($tracking['entered_at'], $now, $pauseTotal, $pauseStart);
    }

    /**
     * Calculate active minutes excluding pauses
     */
    protected function calculateActiveMinutes($startTime, $endTime, $pauseTotal = 0, $pauseStart = null)
    {
        $totalMinutes = $this->calculateMinutesDiff($startTime, $endTime);
        
        // Subtract pause time
        $totalMinutes -= $pauseTotal;
        
        // If currently paused, subtract current pause time
        if ($pauseStart) {
            $currentPauseMinutes = $this->calculateMinutesDiff($pauseStart, $endTime);
            $totalMinutes -= $currentPauseMinutes;
        }

        return max(0, $totalMinutes); // Never return negative time
    }

    /**
     * Calculate minutes difference between two timestamps
     */
    protected function calculateMinutesDiff($startTime, $endTime)
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);
        $diff = $end->diff($start);
        
        return ($diff->days * 1440) + ($diff->h * 60) + $diff->i;
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
        
        if (!isset($data['data']['entered_at'])) {
            $data['data']['entered_at'] = date('Y-m-d H:i:s');
        }
        
        if (!isset($data['data']['time_minutes'])) {
            $data['data']['time_minutes'] = 0;
        }
        
        if (!isset($data['data']['pause_total_minutes'])) {
            $data['data']['pause_total_minutes'] = 0;
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