<?php

namespace App\Models;

use CodeIgniter\Model;

class TodoNotificationModel extends Model
{
    protected $table            = 'todo_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'user_id', 'todo_id', 'type', 'title', 'message', 'is_read', 
        'action_url', 'priority', 'scheduled_at', 'sent_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'type'    => 'required|in_list[overdue,due_today,due_soon,reminder,completed]',
        'title'   => 'required|max_length[255]',
        'message' => 'required|max_length[500]',
        'priority' => 'permit_empty|in_list[low,medium,high,urgent]'
    ];    /**
     * Create overdue notification
     */
    public function createOverdueNotification(int $userId, int $todoId, string $todoTitle, string $dueDate)
    {
        // Calculate days past due using DateTime for accurate calculation
        $due = new \DateTime($dueDate);
        $due->setTime(0, 0, 0); // Set to start of day
        $now = new \DateTime();
        $now->setTime(0, 0, 0); // Set to start of day
        
        $daysPastDue = 0;
        if ($now > $due) {
            $diff = $now->diff($due);
            $daysPastDue = $diff->days;
        }
        
        return $this->insert([
            'user_id' => $userId,
            'todo_id' => $todoId,
            'type' => 'overdue',
            'title' => lang('App.todo_overdue_notification'),
            'message' => sprintf(lang('App.todo_overdue_message'), $todoTitle, $daysPastDue),
            'priority' => 'high',
            'action_url' => base_url("todos/edit/{$todoId}"),
            'is_read' => 0
        ]);
    }

    /**
     * Create due today notification
     */
    public function createDueTodayNotification(int $userId, int $todoId, string $todoTitle)
    {
        return $this->insert([
            'user_id' => $userId,
            'todo_id' => $todoId,
            'type' => 'due_today',
            'title' => lang('App.todo_due_today_notification'),
            'message' => sprintf(lang('App.todo_due_today_message'), $todoTitle),
            'priority' => 'medium',
            'action_url' => base_url("todos/edit/{$todoId}"),
            'is_read' => 0
        ]);
    }

    /**
     * Create due soon notification
     */
    public function createDueSoonNotification(int $userId, int $todoId, string $todoTitle, string $dueDate)
    {
        $daysUntilDue = floor((strtotime($dueDate) - time()) / (60 * 60 * 24));
        
        return $this->insert([
            'user_id' => $userId,
            'todo_id' => $todoId,
            'type' => 'due_soon',
            'title' => lang('App.todo_due_soon_notification'),
            'message' => sprintf(lang('App.todo_due_soon_message'), $todoTitle, $daysUntilDue),
            'priority' => 'medium',
            'action_url' => base_url("todos/edit/{$todoId}"),
            'is_read' => 0
        ]);
    }

    /**
     * Get unread notifications for user
     */
    public function getUnreadNotifications(int $userId, int $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('priority', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all notifications for user
     */
    public function getUserNotifications(int $userId, int $limit = 50, int $offset = 0)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId)
    {
        return $this->update($notificationId, ['is_read' => 1]);
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->set(['is_read' => 1])
                    ->update();
    }

    /**
     * Get notification count for user
     */
    public function getUnreadCount(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * Clean old notifications (older than 30 days)
     */
    public function cleanOldNotifications()
    {
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        return $this->where('created_at <', $thirtyDaysAgo)
                    ->delete();
    }

    /**
     * Check if notification already exists for todo
     */
    public function notificationExists(int $userId, int $todoId, string $type)
    {
        return $this->where('user_id', $userId)
                    ->where('todo_id', $todoId)
                    ->where('type', $type)
                    ->where('created_at >=', date('Y-m-d 00:00:00'))
                    ->countAllResults() > 0;
    }
} 