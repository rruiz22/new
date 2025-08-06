<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\TodoModel;
use App\Models\TodoNotificationModel;

class ProcessTodoNotifications extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Todo';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'todo:process-notifications';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Process overdue and due today todo notifications';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'todo:process-notifications [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--type' => 'Type of notifications to process (overdue, due_today, all)',
        '--force' => 'Force processing even if notifications were sent recently'
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $todoModel = new TodoModel();
        $notificationModel = new TodoNotificationModel();
        
        $type = $params['type'] ?? 'all';
        $force = isset($params['force']);
        
        CLI::write('Starting TODO notification processing...', 'green');
        
        $processedCount = 0;
        
        if ($type === 'overdue' || $type === 'all') {
            $processedCount += $this->processOverdueNotifications($todoModel, $notificationModel, $force);
        }
        
        if ($type === 'due_today' || $type === 'all') {
            $processedCount += $this->processDueTodayNotifications($todoModel, $notificationModel, $force);
        }
        
        if ($type === 'due_soon' || $type === 'all') {
            $processedCount += $this->processDueSoonNotifications($todoModel, $notificationModel, $force);
        }
        
        CLI::write("Processed {$processedCount} notifications successfully.", 'green');
    }
    
    /**
     * Process overdue todo notifications
     */
    private function processOverdueNotifications($todoModel, $notificationModel, $force = false)
    {
        CLI::write('Processing overdue notifications...', 'yellow');
        
        $overdueTodos = $todoModel->getOverdueTodos();
        $count = 0;
        
        foreach ($overdueTodos as $todo) {
            // Check if notification was already sent recently (within 24 hours)
            if (!$force) {
                $recentNotification = $notificationModel
                    ->where('todo_id', $todo['id'])
                    ->where('type', 'overdue')
                    ->where('created_at >', date('Y-m-d H:i:s', strtotime('-24 hours')))
                    ->first();
                    
                if ($recentNotification) {
                    continue;
                }
            }
            
            $daysOverdue = $this->calculateDaysOverdue($todo['due_date']);
            
            $notificationData = [
                'user_id' => $todo['user_id'],
                'todo_id' => $todo['id'],
                'type' => 'overdue',
                'title' => lang('App.todo_overdue_notification'),
                'message' => sprintf(lang('App.todo_overdue_message'), $todo['title'], $daysOverdue),
                'priority' => 'high',
                'action_url' => base_url('todos/edit/' . $todo['id']),
                'is_read' => 0
            ];
            
            if ($notificationModel->insert($notificationData)) {
                $count++;
                CLI::write("  - Created overdue notification for: {$todo['title']}", 'cyan');
            }
        }
        
        CLI::write("Created {$count} overdue notifications.", 'green');
        return $count;
    }
    
    /**
     * Process due today todo notifications
     */
    private function processDueTodayNotifications($todoModel, $notificationModel, $force = false)
    {
        CLI::write('Processing due today notifications...', 'yellow');
        
        $dueTodayTodos = $todoModel->getDueTodayTodos();
        $count = 0;
        
        foreach ($dueTodayTodos as $todo) {
            // Check if notification was already sent today
            if (!$force) {
                $recentNotification = $notificationModel
                    ->where('todo_id', $todo['id'])
                    ->where('type', 'due_today')
                    ->where('DATE(created_at)', date('Y-m-d'))
                    ->first();
                    
                if ($recentNotification) {
                    continue;
                }
            }
            
            $notificationData = [
                'user_id' => $todo['user_id'],
                'todo_id' => $todo['id'],
                'type' => 'due_today',
                'title' => lang('App.todo_due_today_notification'),
                'message' => sprintf(lang('App.todo_due_today_message'), $todo['title']),
                'priority' => 'medium',
                'action_url' => base_url('todos/edit/' . $todo['id']),
                'is_read' => 0
            ];
            
            if ($notificationModel->insert($notificationData)) {
                $count++;
                CLI::write("  - Created due today notification for: {$todo['title']}", 'cyan');
            }
        }
        
        CLI::write("Created {$count} due today notifications.", 'green');
        return $count;
    }
    
    /**
     * Process due soon todo notifications
     */
    private function processDueSoonNotifications($todoModel, $notificationModel, $force = false)
    {
        CLI::write('Processing due soon notifications...', 'yellow');
        
        $dueSoonTodos = $todoModel->getDueSoonTodos(3); // 3 days ahead
        $count = 0;
        
        foreach ($dueSoonTodos as $todo) {
            // Check if notification was already sent for this todo in the last 3 days
            if (!$force) {
                $recentNotification = $notificationModel
                    ->where('todo_id', $todo['id'])
                    ->where('type', 'due_soon')
                    ->where('created_at >', date('Y-m-d H:i:s', strtotime('-3 days')))
                    ->first();
                    
                if ($recentNotification) {
                    continue;
                }
            }
            
            $daysUntilDue = $this->calculateDaysUntilDue($todo['due_date']);
            
            $notificationData = [
                'user_id' => $todo['user_id'],
                'todo_id' => $todo['id'],
                'type' => 'due_soon',
                'title' => lang('App.todo_due_soon_notification'),
                'message' => sprintf(lang('App.todo_due_soon_message'), $todo['title'], $daysUntilDue),
                'priority' => 'low',
                'action_url' => base_url('todos/edit/' . $todo['id']),
                'is_read' => 0
            ];
            
            if ($notificationModel->insert($notificationData)) {
                $count++;
                CLI::write("  - Created due soon notification for: {$todo['title']}", 'cyan');
            }
        }
        
        CLI::write("Created {$count} due soon notifications.", 'green');
        return $count;
    }
      /**
     * Calculate days overdue
     */
    private function calculateDaysOverdue($dueDate)
    {
        $due = new \DateTime($dueDate);
        $due->setTime(0, 0, 0); // Set to start of day
        $now = new \DateTime();
        $now->setTime(0, 0, 0); // Set to start of day
        
        if ($now > $due) {
            $diff = $now->diff($due);
            return $diff->days;
        }
        
        return 0;
    }
      /**
     * Calculate days until due
     */
    private function calculateDaysUntilDue($dueDate)
    {
        $due = new \DateTime($dueDate);
        $due->setTime(0, 0, 0); // Set to start of day
        $now = new \DateTime();
        $now->setTime(0, 0, 0); // Set to start of day
        
        if ($due > $now) {
            $diff = $due->diff($now);
            return $diff->days;
        }
        
        return 0;
    }
}
