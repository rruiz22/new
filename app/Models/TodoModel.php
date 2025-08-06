<?php

namespace App\Models;

use CodeIgniter\Model;

class TodoModel extends Model
{
    protected $table            = 'todos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'title', 'description', 'status', 'priority', 'due_date', 'user_id', 
        'category', 'tags', 'estimated_hours', 'actual_hours', 'reminder_sent',
        'completion_notes', 'assigned_to', 'project_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'           => 'required|min_length[3]|max_length[255]',
        'priority'        => 'required|in_list[low,medium,high,urgent]',
        'status'          => 'required|in_list[pending,in_progress,completed,cancelled]',
        'category'        => 'permit_empty|max_length[100]',
        'estimated_hours' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'actual_hours'    => 'permit_empty|decimal|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get todos by status
     * 
     * @param string $status 'pending', 'in_progress', 'completed', 'cancelled'
     * @param int|null $userId
     */
    public function getByStatus(string $status, int $userId = null)
    {
        $query = $this->where('status', $status);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('priority', 'DESC')
                     ->orderBy('due_date', 'ASC')
                     ->findAll();
    }

    /**
     * Get todos by priority
     * 
     * @param string $priority 'low', 'medium', 'high', 'urgent'
     * @param int|null $userId
     */
    public function getByPriority(string $priority, int $userId = null)
    {
        $query = $this->where('priority', $priority);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('due_date', 'ASC')
                     ->findAll();
    }

    /**
     * Get overdue todos
     * 
     * @param int|null $userId
     * @return array
     */
    public function getOverdueTodos(int $userId = null)
    {
        $query = $this->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where('due_date IS NOT NULL')
                      ->where('due_date <', date('Y-m-d'));
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('due_date', 'ASC')
                     ->orderBy('priority', 'DESC')
                     ->findAll();
    }

    /**
     * Get todos due today
     * 
     * @param int|null $userId
     * @return array
     */
    public function getTodosDueToday(int $userId = null)
    {
        $query = $this->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where('due_date', date('Y-m-d'));
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('priority', 'DESC')
                     ->findAll();
    }

    /**
     * Get todos due this week
     * 
     * @param int|null $userId
     * @return array
     */
    public function getTodosDueThisWeek(int $userId = null)
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        $query = $this->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where('due_date >=', $startOfWeek)
                      ->where('due_date <=', $endOfWeek);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('due_date', 'ASC')
                     ->orderBy('priority', 'DESC')
                     ->findAll();
    }

    /**
     * Get todos by category
     * 
     * @param string $category
     * @param int|null $userId
     * @return array
     */
    public function getByCategory(string $category, int $userId = null)
    {
        $query = $this->where('category', $category);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('priority', 'DESC')
                     ->orderBy('due_date', 'ASC')
                     ->findAll();
    }

    /**
     * Get todo statistics for a user
     * 
     * @param int $userId
     * @return array
     */
    public function getUserStats(int $userId)
    {
        $stats = [
            'total' => $this->where('user_id', $userId)->countAllResults(false),
            'pending' => $this->where('user_id', $userId)->where('status', 'pending')->countAllResults(false),
            'in_progress' => $this->where('user_id', $userId)->where('status', 'in_progress')->countAllResults(false),
            'completed' => $this->where('user_id', $userId)->where('status', 'completed')->countAllResults(false),
            'overdue' => $this->where('user_id', $userId)
                              ->where('status !=', 'completed')
                              ->where('status !=', 'cancelled')
                              ->where('due_date IS NOT NULL')
                              ->where('due_date <', date('Y-m-d'))
                              ->countAllResults(false),
            'due_today' => $this->where('user_id', $userId)
                                ->where('status !=', 'completed')
                                ->where('status !=', 'cancelled')
                                ->where('due_date', date('Y-m-d'))
                                ->countAllResults(false),
            'due_this_week' => $this->where('user_id', $userId)
                                    ->where('status !=', 'completed')
                                    ->where('status !=', 'cancelled')
                                    ->where('due_date >=', date('Y-m-d', strtotime('monday this week')))
                                    ->where('due_date <=', date('Y-m-d', strtotime('sunday this week')))
                                    ->countAllResults(false),
        ];

        // Calculate completion rate
        $stats['completion_rate'] = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100, 1) : 0;
        
        return $stats;
    }

    /**
     * Get todos that need reminder notifications
     * 
     * @param int $daysBefore Number of days before due date to send reminder
     * @return array
     */
    public function getTodosNeedingReminder(int $daysBefore = 1)
    {
        $reminderDate = date('Y-m-d', strtotime("+{$daysBefore} days"));
        
        return $this->where('status !=', 'completed')
                    ->where('status !=', 'cancelled')
                    ->where('due_date', $reminderDate)
                    ->where('reminder_sent', 0)
                    ->findAll();
    }

    /**
     * Mark reminder as sent
     * 
     * @param int $todoId
     * @return bool
     */
    public function markReminderSent(int $todoId)
    {
        return $this->update($todoId, ['reminder_sent' => 1]);
    }

    /**
     * Get todos with advanced filtering
     * 
     * @param array $filters
     * @param int|null $userId
     * @return array
     */
    public function getFilteredTodos(array $filters, int $userId = null)
    {
        $query = $this;
        
        if ($userId !== null) {
            $query = $query->where('user_id', $userId);
        }
        
        if (!empty($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['priority'])) {
            $query = $query->where('priority', $filters['priority']);
        }
        
        if (!empty($filters['category'])) {
            $query = $query->where('category', $filters['category']);
        }
        
        if (!empty($filters['due_date_from'])) {
            $query = $query->where('due_date >=', $filters['due_date_from']);
        }
        
        if (!empty($filters['due_date_to'])) {
            $query = $query->where('due_date <=', $filters['due_date_to']);
        }
        
        if (!empty($filters['search'])) {
            $query = $query->groupStart()
                          ->like('title', $filters['search'])
                          ->orLike('description', $filters['search'])
                          ->orLike('tags', $filters['search'])
                          ->groupEnd();
        }
        
        $orderBy = $filters['order_by'] ?? 'due_date';
        $orderDir = $filters['order_dir'] ?? 'ASC';
        
        return $query->orderBy($orderBy, $orderDir)
                     ->findAll();
    }

    /**
     * Get unique categories for a user
     * 
     * @param int $userId
     * @return array
     */
    public function getUserCategories(int $userId)
    {
        return $this->select('category')
                    ->where('user_id', $userId)
                    ->where('category IS NOT NULL')
                    ->where('category !=', '')
                    ->groupBy('category')
                    ->orderBy('category', 'ASC')
                    ->findColumn('category');
    }

    /**
     * Get productivity insights for a user
     * 
     * @param int $userId
     * @param int $days Number of days to look back
     * @return array
     */
    public function getProductivityInsights(int $userId, int $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        // Get completed tasks in the period
        $completedTasks = $this->where('user_id', $userId)
                               ->where('status', 'completed')
                               ->where('updated_at >=', $startDate)
                               ->countAllResults();
        
        // Calculate average completion time
        $tasksWithHours = $this->select('estimated_hours, actual_hours')
                               ->where('user_id', $userId)
                               ->where('status', 'completed')
                               ->where('actual_hours IS NOT NULL')
                               ->where('actual_hours > 0')
                               ->where('updated_at >=', $startDate)
                               ->findAll();
        
        $avgCompletionTime = 0;
        if (!empty($tasksWithHours)) {
            $totalHours = array_sum(array_column($tasksWithHours, 'actual_hours'));
            $avgCompletionTime = round($totalHours / count($tasksWithHours), 1);
        }
        
        // Tasks per day
        $tasksPerDay = round($completedTasks / $days, 1);
        
        // Most productive day of week
        $dayStats = $this->select('DAYNAME(updated_at) as day_name, COUNT(*) as count')
                         ->where('user_id', $userId)
                         ->where('status', 'completed')
                         ->where('updated_at >=', $startDate)
                         ->groupBy('DAYNAME(updated_at)')
                         ->orderBy('count', 'DESC')
                         ->first();
        
        $mostProductiveDay = $dayStats ? $dayStats['day_name'] : 'N/A';
        
        return [
            'completed_tasks' => $completedTasks,
            'avg_completion_time' => $avgCompletionTime,
            'tasks_per_day' => $tasksPerDay,
            'most_productive_day' => $mostProductiveDay
        ];
    }
    
    /**
     * Get todos due today (alias for command compatibility)
     * 
     * @param int|null $userId
     * @return array
     */
    public function getDueTodayTodos(int $userId = null)
    {
        return $this->getTodosDueToday($userId);
    }
    
    /**
     * Get todos due soon
     * 
     * @param int $days Number of days ahead to check
     * @param int|null $userId
     * @return array
     */
    public function getDueSoonTodos(int $days = 3, int $userId = null)
    {
        $startDate = date('Y-m-d', strtotime('+1 day'));
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $query = $this->where('status !=', 'completed')
                      ->where('status !=', 'cancelled')
                      ->where('due_date >=', $startDate)
                      ->where('due_date <=', $endDate);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('due_date', 'ASC')
                     ->orderBy('priority', 'DESC')
                     ->findAll();
    }

    /**
     * Get todos for a specific user with optional filters
     * 
     * @param int $userId
     * @param array $filters
     * @return array
     */
    public function getTodosForUser(int $userId, array $filters = [])
    {
        $query = $this->where('user_id', $userId);
        
        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->groupStart()
                  ->like('title', $search)
                  ->orLike('description', $search)
                  ->orLike('category', $search)
                  ->orLike('tags', $search)
                  ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (!empty($filters['due_date_from'])) {
            $query->where('due_date >=', $filters['due_date_from']);
        }
        
        if (!empty($filters['due_date_to'])) {
            $query->where('due_date <=', $filters['due_date_to']);
        }
        
        return $query->orderBy('priority', 'DESC')
                     ->orderBy('due_date', 'ASC')
                     ->orderBy('created_at', 'DESC')
                     ->findAll();
    }
} 