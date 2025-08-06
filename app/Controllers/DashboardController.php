<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomUserModel;
use App\Models\TodoModel;
use CodeIgniter\I18n\Time;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $todoModel;

    public function __construct()
    {
        $this->userModel = new CustomUserModel();
        $this->todoModel = new TodoModel();
    }

    public function index()
    {
        // Get current user
        $userId = auth()->id();
        $user = $this->userModel->find($userId);
        
        // Get total users
        $totalUsers = $this->userModel->countAll();
        
        // Get total staff users (admin, manager, staff)
        $totalStaff = $this->userModel->whereIn('user_type', ['admin', 'manager', 'staff'])->countAllResults();
        
        // Get user's pending todos
        $pendingTodos = $this->todoModel->where('assigned_to', $userId)
                                     ->where('status', 'pending')
                                     ->countAllResults();
        
        // Get user's completed todos
        $completedTodos = $this->todoModel->where('assigned_to', $userId)
                                       ->where('status', 'completed')
                                       ->countAllResults();
        
        // Get high priority todos
        $highPriorityTodos = $this->todoModel->where('assigned_to', $userId)
                                         ->where('priority', 'high')
                                         ->where('status', 'pending')
                                         ->countAllResults();
                                         
        // Get upcoming todos (due in next 7 days)
        $now = new Time();
        $next7Days = $now->addDays(7)->toDateString();
        $upcomingTodos = $this->todoModel->where('assigned_to', $userId)
                                      ->where('status', 'pending')
                                      ->where('due_date <=', $next7Days)
                                      ->where('due_date >=', $now->toDateString())
                                      ->orderBy('due_date', 'ASC')
                                      ->findAll();
                                      
        // Format dates according to user preferences
        $dateFormat = $user->date_format ?? 'Y-m-d';
        $timezone = $user->timezone ?? 'UTC';
        
        // Convert todos to arrays if they are objects and format dates
        $upcomingTodosArray = [];
        foreach ($upcomingTodos as $todo) {
            // Convert to array if it's an object
            $todoArray = is_array($todo) ? $todo : $todo->toArray();
            
            if (!empty($todoArray['due_date'])) {
                $date = new \DateTime($todoArray['due_date'], new \DateTimeZone('UTC'));
                $date->setTimezone(new \DateTimeZone($timezone));
                $todoArray['formatted_due_date'] = $date->format($dateFormat);
            } else {
                $todoArray['formatted_due_date'] = '-';
            }
            
            $upcomingTodosArray[] = $todoArray;
        }
        
        // Recent activities - this is just a placeholder
        // In a real app, you'd have an activities log table
        $recentActivities = [
            [
                'type' => 'login',
                'description' => 'You logged in successfully',
                'timestamp' => time() - 3600, // 1 hour ago
            ],
            [
                'type' => 'update',
                'description' => 'Profile information updated',
                'timestamp' => time() - 86400, // 1 day ago
            ],
            [
                'type' => 'todo',
                'description' => 'You completed a todo task',
                'timestamp' => time() - 172800, // 2 days ago
            ],
        ];
        
        // Format activity dates
        foreach ($recentActivities as &$activity) {
            $date = new \DateTime('@' . $activity['timestamp']);
            $date->setTimezone(new \DateTimeZone($timezone));
            $activity['formatted_date'] = $date->format($dateFormat);
        }
        
        $data = [
            'title' => lang('App.dashboard'),
            'totalUsers' => $totalUsers,
            'totalStaff' => $totalStaff,
            'pendingTodos' => $pendingTodos,
            'completedTodos' => $completedTodos,
            'highPriorityTodos' => $highPriorityTodos,
            'upcomingTodos' => $upcomingTodosArray,
            'recentActivities' => $recentActivities,
        ];

        return view('dashboard/index', $data);
    }
} 