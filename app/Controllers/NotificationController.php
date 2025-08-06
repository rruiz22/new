<?php

namespace App\Controllers;

use App\Models\TodoNotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new TodoNotificationModel();
    }
    
    /**
     * AJAX endpoint to get notifications
     * Can be called from anywhere in the application
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }
        
        $notifications = $this->getGlobalNotifications();
        
        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications['notifications'],
            'count' => $notifications['count']
        ]);
    }
    
    /**
     * AJAX endpoint to mark notification as read
     *
     * @param int|null $notificationId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function markRead($notificationId = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }
        
        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid notification ID'
            ]);
        }

        $result = $this->notificationModel->markAsRead($notificationId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? lang('App.notification_marked_read') : 'Failed to mark notification as read'
        ]);
    }
    
    /**
     * AJAX endpoint to mark all notifications as read
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function markAllRead()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }
        
        $userId = auth()->user()->id;
        $result = $this->notificationModel->markAllAsRead($userId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? lang('App.all_notifications_marked_read') : 'Failed to mark notifications as read'
        ]);
    }
} 