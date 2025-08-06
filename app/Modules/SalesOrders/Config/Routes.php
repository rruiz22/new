<?php

namespace Modules\SalesOrders\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Sales Orders Module Routes
 */
return function (RouteCollection $routes) {
    // Sales Orders Module Routes
    $routes->group('sales_orders', ['namespace' => 'Modules\SalesOrders\Controllers', 'filter' => 'session'], function($routes) {
        // Main routes
        $routes->get('/', 'SalesOrdersController::index');
        $routes->get('dashboard_content', 'SalesOrdersController::dashboard_content');
        $routes->get('today_content', 'SalesOrdersController::today_content');
        $routes->get('tomorrow_content', 'SalesOrdersController::tomorrow_content');
        $routes->get('pending_content', 'SalesOrdersController::pending_content');
        $routes->get('week_content', 'SalesOrdersController::week_content');
        $routes->get('all_content', 'SalesOrdersController::all_content');
        $routes->post('all_content', 'SalesOrdersController::all_content'); // For AJAX
        $routes->get('services_content', 'SalesOrdersController::services_content');
        $routes->get('deleted_content', 'SalesOrdersController::deleted_content');
        
        // CRUD operations
        $routes->get('modal_form', 'SalesOrdersController::modal_form');
        $routes->post('store', 'SalesOrdersController::store');
        $routes->get('view/(:num)', 'SalesOrdersController::view/$1');
        $routes->get('print/(:num)', 'SalesOrdersController::print/$1');
        $routes->get('downloadPdf/(:num)', 'SalesOrdersController::downloadPdf/$1');
        
        // Delete operations
        $routes->get('delete/(:num)', 'SalesOrdersController::delete/$1');
        $routes->post('delete/(:num)', 'SalesOrdersController::delete/$1'); // For AJAX
        $routes->post('restore/(:num)', 'SalesOrdersController::restore/$1');
        $routes->post('forceDelete/(:num)', 'SalesOrdersController::forceDelete/$1');
        
        // Statistics and data
        $routes->get('statistics', 'SalesOrdersController::getStatistics');
        $routes->get('dashboard_stats', 'SalesOrdersController::dashboard_stats');
        $routes->get('chart_data', 'SalesOrdersController::chart_data');
        
        // Dashboard widgets
        $routes->get('top_clients', 'SalesOrdersController::top_clients');
        $routes->get('performance_metrics', 'SalesOrdersController::performance_metrics');
        $routes->get('recent_activity', 'SalesOrdersController::recent_activity');
        
        // AJAX endpoints
        $routes->post('updateStatus/(:num)', 'SalesOrdersController::updateStatus/$1');
        $routes->post('sendEmail/(:num)', 'SalesOrdersController::sendEmail/$1');
        $routes->post('sendSMS/(:num)', 'SalesOrdersController::sendSMS/$1');
        $routes->post('sendAlert/(:num)', 'SalesOrdersController::sendAlert/$1');
        $routes->post('addComment/(:num)', 'SalesOrdersController::addComment/$1');
        $routes->post('checkDuplicateOrder', 'SalesOrdersController::checkDuplicateOrder');
        $routes->post('getDuplicateOrders', 'SalesOrdersController::getDuplicateOrders');
        $routes->get('testDuplicateEndpoint', 'SalesOrdersController::testDuplicateEndpoint');
        
        // Activity and comments
        $routes->get('getActivities/(:num)', 'SalesOrdersController::getActivities/$1');
        $routes->get('getActivity/(:num)', 'SalesOrdersController::getActivity/$1');
        $routes->get('getNewActivities/(:num)', 'SalesOrdersController::getNewActivities/$1');
        $routes->get('getComments/(:num)', 'SalesOrdersController::getComments/$1');
        
        // Advanced comment operations
        $routes->post('addReply', 'SalesOrdersController::addReply');
        $routes->post('updateComment/(:num)', 'SalesOrdersController::updateComment/$1');
        $routes->post('deleteComment/(:num)', 'SalesOrdersController::deleteComment/$1');
        
        // Data fetching endpoints
        $routes->get('getServicesForClient/(:num)', 'SalesOrdersController::getServicesForClient/$1');
        $routes->get('getServicesForClient', 'SalesOrdersController::getServicesForClient');
        $routes->get('getServicesForOrderForm/(:num)', 'SalesOrdersController::getServicesForOrderForm/$1');
        $routes->get('getServicesForOrderForm', 'SalesOrdersController::getServicesForOrderForm');
        $routes->get('getContactsForClient/(:num)', 'SalesOrdersController::getContactsForClient/$1');
        $routes->get('getContactsForClient', 'SalesOrdersController::getContactsForClient');
        $routes->get('getOrderData/(:num)', 'SalesOrdersController::getOrderData/$1');
        
        // Followers routes
        $routes->get('getFollowers/(:num)', 'SalesOrdersController::getFollowers/$1');
        $routes->get('getAvailableFollowers/(:num)', 'SalesOrdersController::getAvailableFollowers/$1');
        $routes->post('addFollower', 'SalesOrdersController::addFollower');
        $routes->post('removeFollower', 'SalesOrdersController::removeFollower');
        $routes->post('updateFollowerPreferences', 'SalesOrdersController::updateFollowerPreferences');
        
        // Mentions and users
        $routes->get('getStaffUsers', 'SalesOrdersController::getStaffUsers');
        
        // Debug and test routes (development only)
        $routes->get('test_deleted', 'SalesOrdersController::test_deleted');
        $routes->get('test_activity/(:num)', 'SalesOrdersController::test_activity/$1');
        $routes->get('test_activity', 'SalesOrdersController::test_activity');
        $routes->get('test_users_data', 'SalesOrdersController::test_users_data');
        $routes->get('create_test_users', 'SalesOrdersController::create_test_users');
        $routes->get('debug_pending', 'SalesOrdersController::debug_pending');
        $routes->match(['GET', 'POST'], 'debug_instructions', 'SalesOrdersController::debug_instructions');
        $routes->get('debug_qr', 'SalesOrdersController::debug_qr');
        $routes->match(['GET', 'POST'], 'regenerateQR/(:num)', 'SalesOrdersController::regenerateQR/$1');
        // File attachments (token or session-based security)
        $routes->get('downloadAttachment/(.*)', 'SalesOrdersController::downloadAttachment/$1');
    });

    // Sales Orders Services Routes
    $routes->group('sales_orders_services', ['namespace' => 'Modules\SalesOrders\Controllers', 'filter' => 'session'], function($routes) {
        $routes->get('/', 'SalesOrdersServicesController::index');
        $routes->post('list_data', 'SalesOrdersServicesController::list_data');
        $routes->get('modal_form', 'SalesOrdersServicesController::modal_form');
        $routes->post('store', 'SalesOrdersServicesController::store');
        $routes->get('view/(:num)', 'SalesOrdersServicesController::view/$1');
        $routes->get('get_data/(:num)', 'SalesOrdersServicesController::get_data/$1');
        $routes->get('delete/(:num)', 'SalesOrdersServicesController::delete/$1');
        $routes->get('get_services_json', 'SalesOrdersServicesController::get_services_json');
        $routes->post('toggle_show_in_orders/(:num)', 'SalesOrdersServicesController::toggle_show_in_orders/$1');
        $routes->post('toggle_status/(:num)', 'SalesOrdersServicesController::toggle_status/$1');
        $routes->get('get_services_by_client_json/(:num)', 'SalesOrdersServicesController::get_services_by_client_json/$1');
    });
}; 