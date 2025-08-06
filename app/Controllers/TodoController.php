<?php

namespace App\Controllers;

use App\Models\TodoModel;
use App\Models\TodoNotificationModel;
use CodeIgniter\HTTP\RedirectResponse;
use App\Models\CustomUserModel;

class TodoController extends BaseController
{
    protected $todoModel;
    protected $userModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->todoModel = new TodoModel();
        $this->userModel = new CustomUserModel();
        $this->notificationModel = new TodoNotificationModel();
    }
    
    /**
     * Display a listing of todo items with advanced features
     */
    public function index()
    {
        $todoModel = new TodoModel();
        $userId = auth()->user()->id;
        
        // Get filters from request
        $filters = [
            'search' => $this->request->getGet('search'),
            'status' => $this->request->getGet('status'),
            'priority' => $this->request->getGet('priority'),
            'category' => $this->request->getGet('category'),
            'due_date_from' => $this->request->getGet('due_date_from'),
            'due_date_to' => $this->request->getGet('due_date_to')
        ];
        
        // Get all todos for this user with filters
        $allTodos = $todoModel->getTodosForUser($userId, $filters);
        
        // Separate todos by status
        $pendingTodos = [];
            $completedTodos = [];
        $overdueTodos = [];
        $dueTodayTodos = [];
        
        foreach ($allTodos as &$todo) {
            // Format the todo
            $todo = $this->formatTodoData($todo);
            
            // Categorize todos
            if ($todo['status'] === 'completed') {
                $completedTodos[] = $todo;
            } elseif ($todo['is_overdue']) {
                $overdueTodos[] = $todo;
            } elseif ($todo['is_due_today']) {
                $dueTodayTodos[] = $todo;
            } else {
                $pendingTodos[] = $todo;
            }
        }
        
        // Get statistics
        $stats = $todoModel->getUserStats($userId);
        
        // Get unique categories for filter dropdown
        $categories = $todoModel->getUserCategories($userId);
        
        // Get productivity insights
        $insights = $todoModel->getProductivityInsights($userId);
        
        // Get global notifications (handled by BaseController)
        $globalNotifications = $this->getGlobalNotifications();
        
        $data = [
            'pendingTodos' => $pendingTodos,
            'completedTodos' => $completedTodos,
            'overdueTodos' => $overdueTodos,
            'dueTodayTodos' => $dueTodayTodos,
            'stats' => $stats,
            'categories' => $categories,
            'filters' => $filters,
            'insights' => $insights,
            'notifications' => $globalNotifications['notifications'], // Use global notifications
            'notificationCount' => $globalNotifications['count']
        ];

        return view('todo/index', $data);
    }
    
    /**
     * Show the form for creating a new todo item
     */
    public function create()
    {
        // Get user preferences for date format and timezone
        $userId = auth()->user()->id;
        $user = $this->userModel->find($userId);
        
        // Get user categories
        $categories = $this->todoModel->getUserCategories($userId);
        
        $data = [
            'title' => lang('App.create_todo'),
            'dateFormat' => $user->date_format ?? 'M-d-Y',
            'timezone' => $user->timezone ?? 'UTC',
            'categories' => $categories,
        ];

        return view('todo/create', $data);
    }
    
    /**
     * Store a newly created todo item
     */
    public function store()
    {
        if (!$this->validate($this->todoModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get and sanitize the due date
        $dueDate = $this->request->getPost('due_date');
        
        if (empty($dueDate) || $dueDate === '0000-00-00' || $dueDate === '0000-00-00 00:00:00' || !strtotime($dueDate)) {
            $dueDate = null;
        } else {
            try {
                $dateStr = $dueDate . ' 12:00:00';
                $date = new \DateTime($dateStr);
                $dueDate = $date->format('Y-m-d');
            } catch (\Exception $e) {
                log_message('error', 'Error formatting date: ' . $e->getMessage());
                $dueDate = null;
            }
        }

        // Process tags
        $tags = $this->request->getPost('tags');
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $todoData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status') ?? 'pending',
            'priority' => $this->request->getPost('priority') ?? 'medium',
            'category' => $this->request->getPost('category'),
            'tags' => $tags,
            'estimated_hours' => $this->request->getPost('estimated_hours'),
            'due_date' => $dueDate,
            'user_id' => auth()->user()->id,
        ];

        $todoId = $this->todoModel->save($todoData);

        // Create notification if due date is set and soon
        if ($dueDate && $todoId) {
            $this->createDueDateNotifications($todoId, $todoData['title'], $dueDate);
        }

        return redirect()->to(base_url('todos'))->with('message', lang('App.todo_created'));
    }
    
    /**
     * Show the form for editing the specified todo item
     */
    public function edit($id = null)
    {
        $todo = $this->todoModel->find($id);
        
        if (!$todo) {
            return redirect()->to(base_url('todos'))->with('error', lang('App.todo_not_found'));
        }
        
        // Verify the todo belongs to the current user
        $todoUserId = (int)$todo['user_id'];
        $currentUserId = (int)auth()->user()->id;
        
        if ($todoUserId !== $currentUserId) {
            return redirect()->to(base_url('todos'))->with('error', lang('App.unauthorized'));
        }
        
        // Get user preferences
        $user = $this->userModel->find($currentUserId);
        $userTimezone = $user->timezone ?? 'UTC';
        
        // Get user categories
        $categories = $this->todoModel->getUserCategories($currentUserId);
        
        // Format the date if it exists and is valid
        if (!empty($todo['due_date']) && $todo['due_date'] !== '0000-00-00' && $todo['due_date'] !== '0000-00-00 00:00:00') {
            try {
                $date = new \DateTime($todo['due_date']);
                $todo['due_date'] = $date->format('Y-m-d');
            } catch (\Exception $e) {
                log_message('error', 'Error formatting date for edit: ' . $e->getMessage());
                $todo['due_date'] = '';
            }
        } else {
            $todo['due_date'] = '';
        }

        // Convert tags to array
        if (!empty($todo['tags'])) {
            $todo['tags_array'] = explode(',', $todo['tags']);
        } else {
            $todo['tags_array'] = [];
        }

        $data = [
            'title' => lang('App.edit_todo'),
            'todo' => $todo,
            'categories' => $categories,
            'dateFormat' => $user->date_format ?? 'M-d-Y',
            'timezone' => $userTimezone,
        ];

        return view('todo/edit', $data);
    }
    
    /**
     * Update the specified todo item
     */
    public function update($id = null)
    {
        $todo = $this->todoModel->find($id);
        
        if (!$todo) {
            return redirect()->to(base_url('todos'))->with('error', lang('App.todo_not_found'));
        }
        
        // Verify the todo belongs to the current user
        $todoUserId = (int)$todo['user_id'];
        $currentUserId = (int)auth()->user()->id;
        
        if ($todoUserId !== $currentUserId) {
            return redirect()->to(base_url('todos'))->with('error', lang('App.unauthorized'));
        }

        if (!$this->validate($this->todoModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get and sanitize the due date
        $dueDate = $this->request->getPost('due_date');
        
        if (empty($dueDate) || $dueDate === '0000-00-00' || $dueDate === '0000-00-00 00:00:00' || !strtotime($dueDate)) {
            $dueDate = null;
        } else {
            try {
                $dateStr = $dueDate . ' 12:00:00';
                $date = new \DateTime($dateStr);
                $dueDate = $date->format('Y-m-d');
            } catch (\Exception $e) {
                log_message('error', 'Error formatting date: ' . $e->getMessage());
                $dueDate = null;
            }
        }

        // Process tags
        $tags = $this->request->getPost('tags');
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $updateData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status'),
            'priority' => $this->request->getPost('priority'),
            'category' => $this->request->getPost('category'),
            'tags' => $tags,
            'estimated_hours' => $this->request->getPost('estimated_hours'),
            'actual_hours' => $this->request->getPost('actual_hours'),
            'completion_notes' => $this->request->getPost('completion_notes'),
            'due_date' => $dueDate,
        ];

        $this->todoModel->update($id, $updateData);

        // Create notification if status changed to completed
        if ($updateData['status'] === 'completed' && $todo['status'] !== 'completed') {
            $this->createCompletionNotification($currentUserId, $id, $updateData['title']);
        }

        return redirect()->to(base_url('todos'))->with('message', lang('App.todo_updated'));
    }
    
    /**
     * Toggle the status of a todo item
     */
    public function toggleStatus($id = null)
    {
        $todo = $this->todoModel->find($id);
        
        if (!$todo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.todo_not_found')
            ]);
        }
        
        // Verify the todo belongs to the current user
        $todoUserId = (int)$todo['user_id'];
        $currentUserId = (int)auth()->user()->id;
        
        if ($todoUserId !== $currentUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.unauthorized')
            ]);
        }

        // Toggle status
        $newStatus = ($todo['status'] === 'completed') ? 'pending' : 'completed';
        
        $updateData = ['status' => $newStatus];
        
        // If completing, add completion timestamp
        if ($newStatus === 'completed') {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            $this->createCompletionNotification($currentUserId, $id, $todo['title']);
        }
        
        $result = $this->todoModel->update($id, $updateData);
        
        if ($result) {
            // Get the updated todo with formatted data
            $updatedTodo = $this->todoModel->find($id);
            
            // Get user preferences for date formatting
            $user = $this->userModel->find($currentUserId);
            $dateFormat = $user->date_format ?? 'M-d-Y';
            $timezone = $user->timezone ?? 'UTC';
            
            // Format the todo data for frontend
            $todoArray = [$updatedTodo];
            $this->formatTodoDates($todoArray, $dateFormat, $timezone);
            $formattedTodo = $todoArray[0];
            
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('App.todo_status_updated'),
                'newStatus' => $newStatus,
                'todo' => $formattedTodo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.update_failed')
            ]);
        }
    }
    
    /**
     * Delete the specified todo item
     */
    public function delete($id = null)
    {
        $todo = $this->todoModel->find($id);
        
        if (!$todo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.todo_not_found')
            ]);
        }
        
        // Verify the todo belongs to the current user
        $todoUserId = (int)$todo['user_id'];
        $currentUserId = (int)auth()->user()->id;
        
        if ($todoUserId !== $currentUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.unauthorized')
            ]);
        }

        $result = $this->todoModel->delete($id);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('App.todo_deleted')
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('App.delete_failed')
            ]);
        }
    }

    /**
     * Get overdue todos (AJAX)
     */
    public function getOverdue()
    {
        $userId = auth()->user()->id;
        $overdueTodos = $this->todoModel->getOverdueTodos($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'todos' => $overdueTodos,
            'count' => count($overdueTodos)
        ]);
    }

    /**
     * Get todos due today (AJAX)
     */
    public function getDueToday()
    {
        $userId = auth()->user()->id;
        $dueTodayTodos = $this->todoModel->getTodosDueToday($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'todos' => $dueTodayTodos,
            'count' => count($dueTodayTodos)
        ]);
    }

    /**
     * Get user statistics (AJAX)
     */
    public function getStats()
    {
        $userId = auth()->user()->id;
        $stats = $this->todoModel->getUserStats($userId);
        $insights = $this->todoModel->getProductivityInsights($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats,
            'insights' => $insights
        ]);
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markNotificationRead($notificationId = null)
    {
        if (!$notificationId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid notification ID']);
        }

        $result = $this->notificationModel->markAsRead($notificationId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Notification marked as read' : 'Failed to mark notification as read'
        ]);
    }

    /**
     * Get notifications (AJAX)
     */
    public function getNotifications()
    {
        $userId = auth()->user()->id;
        $notifications = $this->notificationModel->getUnreadNotifications($userId, 10);
        $count = $this->notificationModel->getUnreadCount($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllNotificationsRead()
    {
        $userId = auth()->user()->id;
        $result = $this->notificationModel->markAllAsRead($userId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'All notifications marked as read' : 'Failed to mark notifications as read'
        ]);
    }

    /**
     * Process overdue notifications (for cron job)
     */
    public function processOverdueNotifications()
    {
        $overdueTodos = $this->todoModel->getOverdueTodos();
        $processed = 0;
        
        foreach ($overdueTodos as $todo) {
            // Check if notification already exists for today
            if (!$this->notificationModel->notificationExists($todo['user_id'], $todo['id'], 'overdue')) {
                $this->notificationModel->createOverdueNotification(
                    $todo['user_id'],
                    $todo['id'],
                    $todo['title'],
                    $todo['due_date']
                );
                $processed++;
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'message' => "Processed {$processed} overdue notifications"
        ]);
    }

    /**
     * Process due today notifications (for cron job)
     */
    public function processDueTodayNotifications()
    {
        $dueTodayTodos = $this->todoModel->getTodosDueToday();
        $processed = 0;
        
        foreach ($dueTodayTodos as $todo) {
            // Check if notification already exists for today
            if (!$this->notificationModel->notificationExists($todo['user_id'], $todo['id'], 'due_today')) {
                $this->notificationModel->createDueTodayNotification(
                    $todo['user_id'],
                    $todo['id'],
                    $todo['title']
                );
                $processed++;
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'message' => "Processed {$processed} due today notifications"
        ]);
    }
    
    /**
     * Format todo dates according to user preferences
     */
    private function formatTodoDates(&$todos, $dateFormat, $timezone)
    {
        foreach ($todos as &$todo) {
            if (!empty($todo['due_date']) && $todo['due_date'] !== '0000-00-00' && $todo['due_date'] !== '0000-00-00 00:00:00') {
                try {
                    $date = new \DateTime($todo['due_date']);
                    $todo['formatted_due_date'] = $date->format($dateFormat);
                    
                    // Add days until/past due - normalize dates to start of day for proper comparison
                    $today = new \DateTime();
                    $today->setTime(0, 0, 0); // Set to start of day
                    $dueDate = new \DateTime($todo['due_date']);
                    $dueDate->setTime(0, 0, 0); // Set to start of day
                    
                    if ($dueDate < $today) {
                        $diff = $today->diff($dueDate);
                        $todo['days_overdue'] = $diff->days;
                        $todo['is_overdue'] = true;
                    } else {
                        $diff = $dueDate->diff($today);
                        $todo['days_until_due'] = $diff->days;
                        $todo['is_overdue'] = false;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error formatting date: ' . $e->getMessage());
                    $todo['formatted_due_date'] = '';
                    $todo['is_overdue'] = false;
                }
            } else {
                $todo['formatted_due_date'] = '';
                $todo['is_overdue'] = false;
            }
                }
            }
            
    /**
     * Create due date notifications
     */
    private function createDueDateNotifications($todoId, $title, $dueDate)
    {
        $userId = auth()->user()->id;
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $threeDaysFromNow = date('Y-m-d', strtotime('+3 days'));
        
        // Create notification if due today
        if ($dueDate === $today) {
            $this->notificationModel->createDueTodayNotification($userId, $todoId, $title);
        }
        // Create notification if due within 3 days
        elseif ($dueDate <= $threeDaysFromNow && $dueDate > $today) {
            $this->notificationModel->createDueSoonNotification($userId, $todoId, $title, $dueDate);
        }
    }

    /**
     * Create completion notification
     */
    private function createCompletionNotification($userId, $todoId, $title)
    {
        $this->notificationModel->insert([
            'user_id' => $userId,
            'todo_id' => $todoId,
            'type' => 'completed',
            'title' => lang('App.todo_completed_notification'),
            'message' => sprintf(lang('App.todo_completed_message'), $title),
            'priority' => 'low',
            'action_url' => base_url("todos"),
            'is_read' => 0
        ]);
    }

    /**
     * Search todos (AJAX)
     */
    public function searchTodos()
    {
        $query = $this->request->getGet('q');
        $userId = auth()->user()->id;
        
        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }
        
        $todos = $this->todoModel
            ->where('user_id', $userId)
            ->groupStart()
                ->like('title', $query)
                ->orLike('description', $query)
                ->orLike('category', $query)
                ->orLike('tags', $query)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();
        
        // Format the todos for the frontend
        $user = $this->userModel->find($userId);
        $dateFormat = $user->date_format ?? 'M-d-Y';
        $timezone = $user->timezone ?? 'UTC';
        
        $formattedTodos = [];
        foreach ($todos as $todo) {
            $todoArray = [$todo];
            $this->formatTodoDates($todoArray, $dateFormat, $timezone);
            $formattedTodos[] = $todoArray[0];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'todos' => $formattedTodos,
            'count' => count($formattedTodos)
        ]);
    }

    /**
     * Debug method to check what's happening with todos
     */
    public function debug()
    {
        $userId = auth()->user()->id;
        
        echo "<h1>TODO Debug Information</h1>";
        echo "<pre>";
        
        echo "User ID: $userId\n\n";
        
        // Test direct database query
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, title, status, deleted_at FROM todos WHERE user_id = ? ORDER BY id", [$userId]);
        $rawResults = $query->getResultArray();
        
        echo "=== Raw Database Query ===\n";
        echo "Total rows: " . count($rawResults) . "\n";
        foreach ($rawResults as $row) {
            $deletedStatus = $row['deleted_at'] ? ' [DELETED]' : '';
            echo "ID: {$row['id']}, Title: {$row['title']}, Status: {$row['status']}{$deletedStatus}\n";
        }
        
        // Test model methods
        echo "\n=== Model Methods ===\n";
        
        $pendingTodos = $this->todoModel->getByStatus('pending', $userId);
        echo "Pending TODOs: " . count($pendingTodos) . "\n";
        foreach ($pendingTodos as $todo) {
            echo "  ID: {$todo['id']}, Title: {$todo['title']}, Status: {$todo['status']}\n";
        }
        
        $completedTodos = $this->todoModel->getByStatus('completed', $userId);
        echo "\nCompleted TODOs: " . count($completedTodos) . "\n";
        foreach ($completedTodos as $todo) {
            echo "  ID: {$todo['id']}, Title: {$todo['title']}, Status: {$todo['status']}\n";
        }
        
        // Test findAll
        echo "\n=== Model findAll ===\n";
        $allTodos = $this->todoModel->where('user_id', $userId)->findAll();
        echo "All active TODOs: " . count($allTodos) . "\n";
        foreach ($allTodos as $todo) {
            echo "  ID: {$todo['id']}, Title: {$todo['title']}, Status: {$todo['status']}\n";
        }
        
        // Test stats
        echo "\n=== Statistics ===\n";
        $stats = $this->todoModel->getUserStats($userId);
        print_r($stats);
        
        echo "</pre>";
    }

    /**
     * Format todo data with additional fields
     *
     * @param array $todo
     * @return array
     */
    private function formatTodoData($todo)
    {
        $now = new \DateTime();
        $dueDate = $todo['due_date'] ? new \DateTime($todo['due_date']) : null;
        
        // Calculate if overdue
        $todo['is_overdue'] = $dueDate && $dueDate < $now && $todo['status'] !== 'completed';
        
        // Calculate if due today
        $todo['is_due_today'] = $dueDate && $dueDate->format('Y-m-d') === $now->format('Y-m-d');
        
        // Calculate days overdue or until due
        if ($dueDate) {
            $diff = $now->diff($dueDate);
            if ($todo['is_overdue']) {
                $todo['days_overdue'] = $diff->days;
            } else {
                $todo['days_until_due'] = $diff->days;
            }
            
            // Format due date for display
            $todo['formatted_due_date'] = $dueDate->format('M j, Y');
        }
        
        return $todo;
    }
} 