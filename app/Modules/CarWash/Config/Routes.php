<?php

namespace Modules\CarWash\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Car Wash Module Routes
 */
return function (RouteCollection $routes) {
    // Car Wash Routes
    $routes->group('car_wash', ['namespace' => 'Modules\CarWash\Controllers'], function($routes) {
        // Main routes
        $routes->get('/', 'CarWashController::index');
        $routes->get('index', 'CarWashController::index');
        $routes->get('view/(:num)', 'CarWashController::view/$1');
        $routes->get('edit/(:num)', 'CarWashController::edit/$1');
        
        // AJAX routes
        $routes->post('store', 'CarWashController::store');
        $routes->post('update/(:num)', 'CarWashController::update/$1');
        $routes->post('delete', 'CarWashController::delete');
        $routes->post('restore', 'CarWashController::restore');
        $routes->post('permanent-delete', 'CarWashController::permanentDelete');
        
        // Status management
        $routes->post('update-status', 'CarWashController::updateStatus');
        $routes->post('updateStatus/(:num)', 'CarWashController::updateStatus/$1');
        
        // Data endpoints
        $routes->get('getTodayOrders', 'CarWashController::getTodayOrders');
        $routes->get('getTomorrowOrders', 'CarWashController::getTomorrowOrders');
        $routes->get('getPendingOrders', 'CarWashController::getPendingOrders');
        $routes->get('getAllActiveOrders', 'CarWashController::getAllActiveOrders');
        $routes->get('getAllOrders', 'CarWashController::getAllOrders');
        $routes->get('getDeletedOrders', 'CarWashController::getDeletedOrders');
        $routes->get('getDashboardStats', 'CarWashController::getDashboardStats');
        $routes->post('getDashboardStats', 'CarWashController::getDashboardStats');
        $routes->get('getCommentsPreview/(:num)', 'CarWashController::getCommentsPreview/$1');
        $routes->get('getNotesPreview/(:num)', 'CarWashController::getNotesPreview/$1');
        
        // Dashboard analytics endpoints
        $routes->get('getPopularServices', 'CarWashController::getPopularServices');
        $routes->get('getTopClients', 'CarWashController::getTopClients');
        $routes->get('getOrdersByStatus', 'CarWashController::getOrdersByStatus');
        $routes->get('getOrdersByPriority', 'CarWashController::getOrdersByPriority');
        $routes->get('getDailyOrdersLast7Days', 'CarWashController::getDailyOrdersLast7Days');
        $routes->post('updateMissingPrices', 'CarWashController::updateMissingPrices');
        
        // Content routes
        $routes->get('dashboard_content', 'CarWashController::dashboard_content');
        $routes->get('today_content', 'CarWashController::today_content');
        $routes->get('all_orders_content', 'CarWashController::all_orders_content');
        $routes->get('tomorrow_content', 'CarWashController::tomorrow_content');
        $routes->get('pending_content', 'CarWashController::pending_content');
        $routes->get('services_content', 'CarWashController::services_content');
        $routes->get('deleted_content', 'CarWashController::deleted_content');
        
        // Modal routes
        $routes->get('modal_form', 'CarWashController::modal_form');
        $routes->get('modal_edit/(:num)', 'CarWashController::modal_edit/$1');
        
        // Activity and comments
        $routes->get('getActivity/(:num)', 'CarWashController::getActivity/$1');
        $routes->post('addComment', 'CarWashController::addComment');
        $routes->post('addReply', 'CarWashController::addReply');
        $routes->post('updateComment/(:num)', 'CarWashController::updateComment/$1');
        $routes->post('deleteComment/(:num)', 'CarWashController::deleteComment/$1');
        $routes->get('getComments/(:num)', 'CarWashController::getComments/$1');
        $routes->get('getUsersForMentions', 'CarWashController::getUsersForMentions');
    });

    // Car Wash Notes Routes (Internal Notes for staff)
    $routes->group('car-wash-notes', ['namespace' => 'Modules\CarWash\Controllers', 'filter' => 'session'], function($routes) {
        // Get notes for a specific car wash order
        $routes->get('order/(:num)', 'CarWashNotesController::getOrderNotes/$1');
        
        // CRUD operations
        $routes->post('create', 'CarWashNotesController::create');
        $routes->post('update/(:num)', 'CarWashNotesController::update/$1');
        $routes->delete('delete/(:num)', 'CarWashNotesController::delete/$1');
        $routes->post('delete/(:num)', 'CarWashNotesController::delete/$1'); // Support POST for delete
        
        // Reply operations
        $routes->post('add-reply', 'CarWashNotesController::addReply');
        
        // File download
        $routes->get('download/(:any)', 'CarWashNotesController::download/$1');
        
        // Staff users for mentions
        $routes->get('staff-users', 'CarWashNotesController::getStaffUsers');
        
        // Mention management
        $routes->post('mark-mention-read/(:num)', 'CarWashNotesController::markMentionRead/$1');
        $routes->get('unread-mentions', 'CarWashNotesController::getUnreadMentions');
    });

    // Reopen car_wash group for remaining routes
    $routes->group('car_wash', ['namespace' => 'Modules\CarWash\Controllers'], function($routes) {
        // Services management
        $routes->get('getServices', 'CarWashController::getServices');
        $routes->get('getServicesForClient/(:num)', 'CarWashController::getServicesForClient/$1');
        $routes->get('getContactsForClient/(:num)', 'CarWashController::getContactsForClient/$1');
        $routes->get('getActiveClients', 'CarWashController::getActiveClients');
        $routes->get('getFormData', 'CarWashController::getFormData');
        
        // Services CRUD
        $routes->post('services/data', 'CarWashServicesController::getServicesData');
        $routes->post('services/store', 'CarWashServicesController::store');
        $routes->post('services/update/(:num)', 'CarWashServicesController::update/$1');
        $routes->get('services/show/(:num)', 'CarWashServicesController::show/$1');
        $routes->post('services/delete/(:num)', 'CarWashServicesController::delete/$1');
        $routes->post('services/toggle-status/(:num)', 'CarWashServicesController::toggleStatus/$1');
        $routes->post('services/toggle-visibility-type/(:num)', 'CarWashServicesController::toggleVisibilityType/$1');
        $routes->get('services/load-clients', 'CarWashServicesController::loadClients');
        
        // Notifications
        $routes->post('sendEmail/(:num)', 'CarWashController::sendEmail/$1');
        $routes->post('sendSMS/(:num)', 'CarWashController::sendSMS/$1');
        
        // QR Code
        $routes->get('regenerateQR/(:num)', 'CarWashController::regenerateQR/$1');
        $routes->post('regenerateQR/(:num)', 'CarWashController::regenerateQR/$1');
        
        // Validation
        $routes->post('checkDuplicateOrder', 'CarWashController::checkDuplicateOrder');
        $routes->post('checkRecentDuplicates', 'CarWashController::checkRecentDuplicates');
        
        // Debug endpoints
        $routes->get('debugDuplicates', 'CarWashController::debugDuplicates');
        
        // File serving route - supports subdirectories like thumbnails  
        $routes->get('file/(:segment)/(:segment)/(:segment)', 'CarWashController::serveAttachment/$1/$2/$3');
        
        // Debug route to test if routes are working
        $routes->get('test-route', 'CarWashController::testRoute');
        
        // Debug attachment route
        $routes->get('test-attachment/(:num)/(:any)/(:any)', 'CarWashController::testAttachment/$1/$2/$3');
        
        // Debug real attachment route
        $routes->get('debug-attachment/(:num)/(:any)/(:any)', 'CarWashController::debugAttachment/$1/$2/$3');
        
        // Simple test route
        $routes->get('serve-file-test', 'CarWashController::serveFileTest');
    });
}; 