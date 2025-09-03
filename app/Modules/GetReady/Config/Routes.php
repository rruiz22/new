<?php

namespace Modules\GetReady\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Get Ready Module Routes
 */
return function (RouteCollection $routes) {
    // Get Ready Module Routes
    $routes->group('get-ready', ['namespace' => 'Modules\GetReady\Controllers', 'filter' => 'session'], function($routes) {
        // Main dashboard
        $routes->get('/', 'GetReadyController::index');
        $routes->get('dashboard', 'GetReadyController::dashboard');
        $routes->get('dashboard_content', 'GetReadyController::dashboard_content');
        
        // Step views
        $routes->get('step/(:segment)', 'GetReadyController::stepView/$1');
        $routes->get('step-content/(:segment)', 'GetReadyController::stepContent/$1');
        
        // Individual step endpoints
        $routes->get('in-transit', 'GetReadyController::inTransit');
        $routes->get('in-transit-content', 'GetReadyController::inTransitContent');
        $routes->get('in-detail', 'GetReadyController::inDetail');
        $routes->get('in-detail-content', 'GetReadyController::inDetailContent');
        $routes->get('in-service', 'GetReadyController::inService');
        $routes->get('in-service-content', 'GetReadyController::inServiceContent');
        $routes->get('in-bodyshop', 'GetReadyController::inBodyshop');
        $routes->get('in-bodyshop-content', 'GetReadyController::inBodyshopContent');
        
        // Service Manager interface
        $routes->get('service-manager', 'GetReadyController::serviceManager');
        $routes->get('service-manager-content', 'GetReadyController::serviceManagerContent');
        
        // Vehicle CRUD operations
        $routes->get('modal_form', 'GetReadyController::modal_form');
        $routes->post('store', 'GetReadyController::store');
        $routes->get('view/(:num)', 'GetReadyController::view/$1');
        $routes->get('edit/(:num)', 'GetReadyController::edit/$1');
        $routes->post('update/(:num)', 'GetReadyController::update/$1');
        $routes->post('delete/(:num)', 'GetReadyController::delete/$1');
        
        // Vehicle actions
        $routes->post('move-to-step/(:num)/(:num)', 'GetReadyController::moveToStep/$1/$2');
        $routes->post('assign-tech/(:num)/(:num)', 'GetReadyController::assignTech/$1/$2');
        $routes->post('update-location/(:num)', 'GetReadyController::updateLocation/$1');
        $routes->post('add-photos/(:num)', 'GetReadyController::addPhotos/$1');
        
        // Print functionality
        $routes->get('print/(:num)', 'GetReadyController::print/$1');
        $routes->get('downloadPdf/(:num)', 'GetReadyController::downloadPdf/$1');
        $routes->get('print-get-ready-sheet/(:num)', 'GetReadyController::printGetReadySheet/$1');
        
        // Statistics and metrics
        $routes->get('statistics', 'GetReadyController::getStatistics');
        $routes->get('dashboard_stats', 'GetReadyController::dashboard_stats');
        $routes->get('step-metrics/(:segment)', 'GetReadyController::stepMetrics/$1');
        $routes->get('performance-data', 'GetReadyController::performanceData');
        
        // Step Management (Admin)
        $routes->get('manage-steps', 'GetReadyController::manageSteps');
        $routes->post('steps/store', 'GetReadyController::storeStep');
        $routes->post('steps/update/(:num)', 'GetReadyController::updateStep/$1');
        $routes->post('steps/delete/(:num)', 'GetReadyController::deleteStep/$1');
        $routes->post('steps/reorder', 'GetReadyController::reorderSteps');
    });

    // Get Ready API Routes (for AJAX and mobile)
    $routes->group('api/get-ready', ['namespace' => 'Modules\GetReady\Controllers', 'filter' => 'session'], function($routes) {
        // Vehicle data endpoints
        $routes->get('vehicles/(:segment)', 'GetReadyApiController::getVehiclesByStep/$1');
        $routes->get('vehicle/(:num)', 'GetReadyApiController::getVehicle/$1');
        $routes->get('vehicle-modal/(:num)', 'GetReadyApiController::getVehicleModal/$1');
        
        // Time tracking
        $routes->get('time-tracking/(:num)', 'GetReadyApiController::getTimeTracking/$1');
        $routes->post('time-tracking/pause/(:num)', 'GetReadyApiController::pauseTimer/$1');
        $routes->post('time-tracking/resume/(:num)', 'GetReadyApiController::resumeTimer/$1');
        
        // Step data
        $routes->get('steps', 'GetReadyApiController::getSteps');
        $routes->get('step-metrics/(:segment)', 'GetReadyApiController::getStepMetrics/$1');
        
        // Service assignments
        $routes->get('available-techs', 'GetReadyApiController::getAvailableTechs');
        $routes->post('assign-tech/(:num)/(:num)', 'GetReadyApiController::assignTech/$1/$2');
        $routes->get('tech-workload/(:num)', 'GetReadyApiController::getTechWorkload/$1');
        
        // Mobile/NFC endpoints
        $routes->post('move-vehicle', 'GetReadyApiController::moveVehicle');
        $routes->post('scan-nfc', 'GetReadyApiController::scanNFC');
        $routes->post('update-location', 'GetReadyApiController::updateLocation');
        
        // Photo management
        $routes->post('upload-photos/(:num)', 'GetReadyApiController::uploadPhotos/$1');
        $routes->get('photos/(:num)', 'GetReadyApiController::getPhotos/$1');
        $routes->post('delete-photo/(:num)', 'GetReadyApiController::deletePhoto/$1');
        
        // Activities and dashboard data
        $routes->get('activities/recent', 'GetReadyApiController::getRecentActivities');
        $routes->get('dashboard-stats', 'GetReadyApiController::getDashboardStats');
    });

    // NFC Scanning Routes (public access)
    $routes->group('nfc/get-ready', ['namespace' => 'Modules\GetReady\Controllers'], function($routes) {
        $routes->get('(:segment)', 'GetReadyNFCController::scan/$1');
        $routes->post('update/(:segment)', 'GetReadyNFCController::update/$1');
        $routes->get('mobile/(:segment)', 'GetReadyNFCController::mobileInterface/$1');
    });
};