<?php

namespace Modules\ServiceOrders\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Service Orders Module Routes
 */
return function (RouteCollection $routes) {
    // Service Orders Module Routes
    $routes->group('service_orders', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'session'], function($routes) {
        // Main routes
        $routes->get('/', 'ServiceOrdersController::index');
        $routes->get('dashboard_content', 'ServiceOrdersController::dashboard_content');
        $routes->get('today_content', 'ServiceOrdersController::today_content');
        $routes->post('today_content', 'ServiceOrdersController::today_content'); // For AJAX
        $routes->get('tomorrow_content', 'ServiceOrdersController::tomorrow_content');
        $routes->post('tomorrow_content', 'ServiceOrdersController::tomorrow_content'); // For AJAX
        $routes->get('pending_content', 'ServiceOrdersController::pending_content');
        $routes->post('pending_content', 'ServiceOrdersController::pending_content'); // For AJAX
        $routes->get('week_content', 'ServiceOrdersController::week_content');
        $routes->post('week_content', 'ServiceOrdersController::week_content'); // For AJAX
        $routes->get('all_content', 'ServiceOrdersController::all_content');
        $routes->post('all_content', 'ServiceOrdersController::all_content'); // For AJAX
        $routes->get('services_content', 'ServiceOrdersController::services_content');
        $routes->get('deleted_content', 'ServiceOrdersController::deleted_content');
        
        // CRUD operations
        $routes->get('modal_form', 'ServiceOrdersController::modal_form');
        $routes->post('store', 'ServiceOrdersController::store');
        $routes->get('view/(:num)', 'ServiceOrdersController::view/$1');
        $routes->get('edit/(:num)', 'ServiceOrdersController::edit/$1');
        $routes->post('update/(:num)', 'ServiceOrdersController::update/$1');
        $routes->get('print/(:num)', 'ServiceOrdersController::print/$1');
        $routes->get('downloadPdf/(:num)', 'ServiceOrdersController::downloadPdf/$1');
        
        // Delete operations
        $routes->get('delete/(:num)', 'ServiceOrdersController::delete/$1');
        $routes->post('delete/(:num)', 'ServiceOrdersController::delete/$1'); // For AJAX
        $routes->post('delete', 'ServiceOrdersController::delete'); // For AJAX without ID in URL
        $routes->post('restore/(:num)', 'ServiceOrdersController::restore/$1');
        $routes->post('restore', 'ServiceOrdersController::restore'); // For AJAX without ID in URL
        $routes->post('permanent-delete/(:num)', 'ServiceOrdersController::permanentDelete/$1');
        $routes->post('permanent-delete', 'ServiceOrdersController::permanentDelete'); // For AJAX without ID in URL
        $routes->post('forceDelete/(:num)', 'ServiceOrdersController::forceDelete/$1');
        
        // Statistics and data
        $routes->get('statistics', 'ServiceOrdersController::getStatistics');
        $routes->get('dashboard_stats', 'ServiceOrdersController::dashboard_stats');
        $routes->get('chart_data', 'ServiceOrdersController::chart_data');
        
        // Dashboard widgets
        $routes->get('top_clients', 'ServiceOrdersController::top_clients');
        $routes->get('performance_metrics', 'ServiceOrdersController::performance_metrics');
        $routes->get('recent_activity', 'ServiceOrdersController::recent_activity');
        
        // AJAX endpoints for DataTables
        $routes->get('get-today-orders', 'ServiceOrdersController::getTodayOrders');
        $routes->get('get-tomorrow-orders', 'ServiceOrdersController::getTomorrowOrders');
        $routes->get('get-pending-orders', 'ServiceOrdersController::getPendingOrders');
        $routes->get('get-week-orders', 'ServiceOrdersController::getWeekOrders');
        $routes->get('get-all-orders', 'ServiceOrdersController::getAllOrders');
        $routes->get('get-deleted-orders', 'ServiceOrdersController::getDeletedOrders');
        $routes->get('get-dashboard-stats', 'ServiceOrdersController::getDashboardStats');
        $routes->get('dashboard-data', 'ServiceOrdersController::getDashboardStats');
        $routes->get('get-services', 'ServiceOrdersController::getServices');
        
        // AJAX endpoints
        $routes->post('updateStatus/(:num)', 'ServiceOrdersController::updateStatus/$1');
        $routes->post('update-status', 'ServiceOrdersController::updateStatus'); // For AJAX without ID in URL
        $routes->post('sendEmail/(:num)', 'ServiceOrdersController::sendEmail/$1');
        $routes->post('sendSMS/(:num)', 'ServiceOrdersController::sendSMS/$1');
        $routes->post('sendAlert/(:num)', 'ServiceOrdersController::sendAlert/$1');
        $routes->post('regenerateQR/(:num)', 'ServiceOrdersController::regenerateQR/$1');
        $routes->get('getStaffUsers', 'ServiceOrdersController::getStaffUsers');
        
        // Followers routes
        $routes->get('getFollowers/(:num)', 'ServiceOrdersController::getFollowers/$1');
        $routes->get('getAvailableFollowers/(:num)', 'ServiceOrdersController::getAvailableFollowers/$1');
        $routes->post('addFollower', 'ServiceOrdersController::addFollower');
        $routes->post('removeFollower', 'ServiceOrdersController::removeFollower');
        $routes->post('updateFollowerPreferences', 'ServiceOrdersController::updateFollowerPreferences');
        $routes->post('addComment/(:num)', 'ServiceOrdersController::addComment/$1');
        $routes->post('addComment', 'ServiceOrdersController::addComment'); // For AJAX without ID in URL
        $routes->post('addReply', 'ServiceOrdersController::addReply');
        $routes->post('updateComment/(:num)', 'ServiceOrdersController::updateComment/$1');
        $routes->post('deleteComment/(:num)', 'ServiceOrdersController::deleteComment/$1');
        $routes->post('checkDuplicateOrder', 'ServiceOrdersController::checkDuplicateOrder');
        $routes->post('getDuplicateOrders', 'ServiceOrdersController::getDuplicateOrders');
        $routes->get('testDuplicateEndpoint', 'ServiceOrdersController::testDuplicateEndpoint');
        
        // Activity and comments
        $routes->get('getActivities/(:num)', 'ServiceOrdersController::getActivities/$1');
        $routes->get('getActivity/(:num)', 'ServiceOrdersController::getActivity/$1');
        $routes->get('getNewActivities/(:num)', 'ServiceOrdersController::getNewActivities/$1');
        $routes->get('getComments/(:num)', 'ServiceOrdersController::getComments/$1');
        $routes->get('getCommentsPreview/(:num)', 'ServiceOrdersController::getCommentsPreview/$1');
        $routes->get('getNotesPreview/(:num)', 'ServiceOrdersController::getNotesPreview/$1');
        
        // Data fetching endpoints
        $routes->get('getServicesForClient/(:num)', 'ServiceOrdersController::getServicesForClient/$1');
        $routes->get('getServicesForClient', 'ServiceOrdersController::getServicesForClient');
        $routes->get('getServicesForOrderForm/(:num)', 'ServiceOrdersController::getServicesForOrderForm/$1');
        $routes->get('getServicesForOrderForm', 'ServiceOrdersController::getServicesForOrderForm');
        $routes->get('getContactsForClient/(:num)', 'ServiceOrdersController::getContactsForClient/$1');
        $routes->get('getContactsForClient', 'ServiceOrdersController::getContactsForClient');
        $routes->get('getOrderData/(:num)', 'ServiceOrdersController::getOrderData/$1');
        
        // Debug and test routes (development only)
        $routes->get('test_deleted', 'ServiceOrdersController::test_deleted');
        $routes->get('test_activity/(:num)', 'ServiceOrdersController::test_activity/$1');
        $routes->get('test_activity', 'ServiceOrdersController::test_activity');
        $routes->get('test_users_data', 'ServiceOrdersController::test_users_data');
        $routes->get('create_test_users', 'ServiceOrdersController::create_test_users');
        $routes->get('debug_pending', 'ServiceOrdersController::debug_pending');
        $routes->match(['GET', 'POST'], 'debug_instructions', 'ServiceOrdersController::debug_instructions');
        $routes->get('debug_qr', 'ServiceOrdersController::debug_qr');
        $routes->get('test_session', 'ServiceOrdersController::test_session');
        $routes->get('debug_endpoints', 'ServiceOrdersController::debug_endpoints');
    });

    // Service Order Notes Routes (within Service Orders module)
    $routes->group('service-order-notes', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'session'], function($routes) {
        // Get notes for a specific service order
        $routes->get('order/(:num)', 'ServiceOrderNotesController::getOrderNotes/$1');
        
        // CRUD operations
        $routes->post('create', 'ServiceOrderNotesController::create');
        $routes->post('update/(:num)', 'ServiceOrderNotesController::update/$1');
        $routes->delete('delete/(:num)', 'ServiceOrderNotesController::delete/$1');
        $routes->post('delete/(:num)', 'ServiceOrderNotesController::delete/$1'); // Support POST for delete
        
        // Reply operations
        $routes->post('add-reply', 'ServiceOrderNotesController::addReply');
        
        // File download
        $routes->get('download/(:any)', 'ServiceOrderNotesController::download/$1');
        
        // Staff users for mentions
        $routes->get('staff-users', 'ServiceOrderNotesController::getStaffUsers');
        
        // Mention management
        $routes->post('mark-mention-read/(:num)', 'ServiceOrderNotesController::markMentionRead/$1');
        $routes->get('unread-mentions', 'ServiceOrderNotesController::getUnreadMentions');
    });

    // Service Orders Services Routes
    $routes->group('service_orders_services', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'session'], function($routes) {
        $routes->get('/', 'ServiceOrdersServicesController::index');
        $routes->post('list_data', 'ServiceOrdersServicesController::list_data');
        $routes->get('modal_form', 'ServiceOrdersServicesController::modal_form');
        $routes->post('store', 'ServiceOrdersServicesController::store');
        $routes->get('view/(:num)', 'ServiceOrdersServicesController::view/$1');
        $routes->get('get_data/(:num)', 'ServiceOrdersServicesController::get_data/$1');
        
        // Delete routes - support both GET and POST
        $routes->get('delete/(:num)', 'ServiceOrdersServicesController::delete/$1');
        $routes->post('delete/(:num)', 'ServiceOrdersServicesController::delete/$1');
        $routes->post('delete', 'ServiceOrdersServicesController::delete'); // For AJAX without ID in URL
        
        $routes->get('get_services_json', 'ServiceOrdersServicesController::get_services_json');
        $routes->post('toggle_show_in_orders/(:num)', 'ServiceOrdersServicesController::toggle_show_in_orders/$1');
        $routes->post('toggle_status/(:num)', 'ServiceOrdersServicesController::toggle_status/$1');
        $routes->get('get_services_by_client_json/(:num)', 'ServiceOrdersServicesController::get_services_by_client_json/$1');
    });
}; 