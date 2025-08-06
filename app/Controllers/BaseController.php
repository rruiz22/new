<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\TodoNotificationModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Todo Notification Model
     *
     * @var TodoNotificationModel
     */
    protected $notificationModel;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        
        // Initialize notification model for global use
        $this->notificationModel = new TodoNotificationModel();
    }
    
    /**
     * Get global notifications for current user
     * Used in topbar across all pages
     *
     * @return array
     */
    protected function getGlobalNotifications()
    {
        if (!auth()->loggedIn()) {
            return ['notifications' => [], 'count' => 0];
        }
        
        $userId = auth()->user()->id;
        $notifications = $this->notificationModel->getUnreadNotifications($userId, 10);
        $count = $this->notificationModel->getUnreadCount($userId);
        
        return [
            'notifications' => $notifications,
            'count' => $count
        ];
    }
    
    /**
     * AJAX endpoint to get notifications
     * Can be called from anywhere in the application
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getNotifications()
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
    public function markNotificationRead($notificationId = null)
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
    public function markAllNotificationsRead()
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
