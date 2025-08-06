<?php

namespace Modules\ReconOrders\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Recon Orders Module Routes
 */
return function (RouteCollection $routes) {
    // Recon Orders Routes with authentication filter
    $routes->group('recon_orders', ['namespace' => 'Modules\ReconOrders\Controllers', 'filter' => 'sessionauth'], function($routes) {
        // Main routes
        $routes->get('/', 'ReconOrdersController::index');
        $routes->get('view/(:num)', 'ReconOrdersController::view/$1');
        $routes->get('edit/(:num)', 'ReconOrdersController::edit/$1');
        
        // CRUD operations
        $routes->post('store', 'ReconOrdersController::store');
        $routes->post('update/(:num)', 'ReconOrdersController::update/$1');
        $routes->post('delete/(:num)', 'ReconOrdersController::delete/$1');
        
        // Tab content routes (GET for views, POST for AJAX data)
        $routes->get('dashboard_content', 'ReconOrdersController::dashboard_content');
        $routes->post('dashboard_content', 'ReconOrdersController::dashboard_content');
        
        $routes->get('today_content', 'ReconOrdersController::today_content');
        $routes->post('today_content', 'ReconOrdersController::today_content');
        
        $routes->get('all_orders_content', 'ReconOrdersController::all_orders_content');
        $routes->post('all_orders_content', 'ReconOrdersController::all_orders_content');
        
        $routes->get('deleted_content', 'ReconOrdersController::deleted_content');
        $routes->post('deleted_content', 'ReconOrdersController::deleted_content');
        
        $routes->get('services_content', 'ReconOrdersController::services_content');
        $routes->post('services_content', 'ReconOrdersController::services_content');
        
        // Data endpoints for DataTables
        $routes->get('getDashboardOrders', 'ReconOrdersController::getDashboardOrders');
        $routes->get('getTodayOrders', 'ReconOrdersController::getTodayOrders');
        $routes->get('getAllActiveOrders', 'ReconOrdersController::getAllActiveOrders');
        $routes->get('getDeletedOrders', 'ReconOrdersController::getDeletedOrders');
        
        // Modal endpoints
        $routes->get('modal_form', 'ReconOrdersController::modal_form');
        $routes->get('modal_edit/(:num)', 'ReconOrdersController::modal_edit/$1');
        
        // Special endpoints
        $routes->post('updateStatus/(:num)', 'ReconOrdersController::updateStatus/$1');
        
        // Notes endpoints
        $routes->get('getNotes/(:num)', 'ReconOrdersController::getNotes/$1');
        $routes->post('addNote/(:num)', 'ReconOrdersController::addNote/$1');
        $routes->post('addNoteReply', 'ReconOrdersController::addNoteReply');
        $routes->post('updateNote/(:num)', 'ReconOrdersController::updateNote/$1');
        $routes->post('deleteNote/(:num)', 'ReconOrdersController::deleteNote/$1');
        $routes->post('updateNoteReply/(:num)', 'ReconOrdersController::updateNoteReply/$1');
        $routes->post('deleteNoteReply/(:num)', 'ReconOrdersController::deleteNoteReply/$1');

        // Comments and Communication endpoints
        $routes->get('getComments/(:num)', 'ReconOrdersController::getComments/$1');
        $routes->get('testComments/(:num)', 'ReconOrdersController::testComments/$1');
        $routes->post('addComment/(:num)', 'ReconOrdersController::addComment/$1');
        $routes->post('updateComment/(:num)', 'ReconOrdersController::updateComment/$1');  
        $routes->post('deleteComment/(:num)', 'ReconOrdersController::deleteComment/$1');
        $routes->post('addReply', 'ReconOrdersController::addReply');
        $routes->get('getUsersForMentions', 'ReconOrdersController::getUsersForMentions');
        
        // File serving endpoints
        $routes->get('attachment/(:num)/(:any)', 'ReconOrdersController::serveAttachment/$1/$2');
        
        // Followers endpoints
        $routes->get('getFollowers/(:num)', 'ReconOrdersController::getFollowers/$1');
        $routes->get('getAvailableFollowers/(:num)', 'ReconOrdersController::getAvailableFollowers/$1');
        $routes->post('addFollower', 'ReconOrdersController::addFollower');
        $routes->delete('removeFollower/(:num)', 'ReconOrdersController::removeFollower/$1');
        
        // Activity and QR endpoints
        $routes->get('getActivity/(:num)', 'ReconOrdersController::getActivity/$1');
        $routes->post('generateQRCode/(:num)', 'ReconOrdersController::generateQRCode/$1');
        $routes->post('regenerateQR/(:num)', 'ReconOrdersController::regenerateQR/$1');
        $routes->post('updatePicturesStatus/(:num)', 'ReconOrdersController::updatePicturesStatus/$1');
        $routes->get('pdf/(:num)', 'ReconOrdersController::pdf/$1');
        
        // Services routes
        $routes->get('services/view/(:num)', 'ReconOrdersController::servicesView/$1');
        $routes->get('services/show/(:num)', 'ReconOrdersController::servicesShow/$1');
        $routes->post('services/store', 'ReconOrdersController::servicesStore');
        $routes->post('services/update/(:num)', 'ReconOrdersController::servicesUpdate/$1');
        $routes->post('services/delete/(:num)', 'ReconOrdersController::servicesDelete/$1');
        $routes->post('services/toggle-status/(:num)', 'ReconOrdersController::servicesToggleStatus/$1');
        $routes->post('services/toggle-visibility/(:num)', 'ReconOrdersController::servicesToggleVisibility/$1');
        
        // Order management routes
        $routes->get('getClients', 'ReconOrdersController::getClients');
        $routes->post('restore/(:num)', 'ReconOrdersController::restore/$1');
        $routes->post('force_delete/(:num)', 'ReconOrdersController::force_delete/$1');
        $routes->delete('permanent_delete/(:num)', 'ReconOrdersController::permanent_delete/$1');
        
        // Internal notes - using generic system
        
        // Services management
        $routes->get('getServices', 'ReconOrdersController::getServices');
        $routes->get('getServicesForClient/(:num)', 'ReconOrdersController::getServicesForClient/$1');
        $routes->get('getActiveServices', 'ReconOrdersController::getActiveServices');
        
        // Services CRUD
        $routes->post('services/data', 'ReconServicesController::getServicesData');
        $routes->post('services/store', 'ReconServicesController::store');
        $routes->post('services/update/(:num)', 'ReconServicesController::update/$1');
        $routes->get('services/show/(:num)', 'ReconServicesController::show/$1');
        $routes->post('services/delete/(:num)', 'ReconServicesController::delete/$1');
        $routes->post('services/toggle-status/(:num)', 'ReconServicesController::toggleStatus/$1');
        $routes->post('services/toggle-visibility-type/(:num)', 'ReconServicesController::toggleVisibilityType/$1');
        $routes->get('services/load-clients', 'ReconServicesController::loadClients');
        $routes->get('services/stats', 'ReconServicesController::getServiceStats');
        $routes->get('services/popular', 'ReconServicesController::getPopularServices');
        $routes->get('services/category/(:segment)', 'ReconServicesController::getServicesByCategory/$1');
        
        // Vehicles routes
        $routes->get('vehicles', 'ReconOrdersController::vehicles');
        $routes->get('vehicles/view/(:num)', 'ReconOrdersController::vehicleView/$1');
        $routes->get('vehicles/search', 'ReconOrdersController::vehicleSearch');
        $routes->post('vehicles/data', 'ReconOrdersController::getVehiclesData');
        $routes->get('vehicles/stats', 'ReconOrdersController::getVehicleStats');
    }); 
}; 