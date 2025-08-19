<?php

namespace Modules\Vehicles\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Vehicles Module Routes
 */
return function (RouteCollection $routes) {
    // Vehicles Routes with authentication filter
    $routes->group('vehicles', ['namespace' => 'Modules\Vehicles\Controllers', 'filter' => 'sessionauth'], function($routes) {
    // Main routes
    $routes->get('/', 'VehiclesController::index');
    $routes->get('search', 'VehiclesController::search');
    
    // Data endpoints (put these before VIN route to avoid conflicts)
    $routes->post('data', 'VehiclesController::getVehiclesData');
    $routes->get('stats', 'VehiclesController::getVehicleStats');
    $routes->get('analytics-data', 'VehiclesController::getAnalyticsData');
    $routes->get('all-vehicles-data', 'VehiclesController::getAllVehiclesData');
    $routes->get('dashboard-data', 'VehiclesController::getDashboardData');
    $routes->get('recent-vehicles-data', 'VehiclesController::getRecentVehiclesData');
    $routes->get('active-vehicles-data', 'VehiclesController::getActiveVehiclesData');
    $routes->get('location-tracking-data', 'VehiclesController::getLocationTrackingData');
    
    // Filter options endpoints
    $routes->get('filter-options/clients', 'VehiclesController::getClientFilterOptions');
    $routes->get('filter-options/makes', 'VehiclesController::getMakeFilterOptions');
    $routes->get('filter-options/years', 'VehiclesController::getYearFilterOptions');
    
    // Action endpoints
    $routes->post('generate-nfc-token', 'VehiclesController::generateNfcToken');
    $routes->post('generate-qr', 'VehiclesController::generateManualVehicleQR');
    $routes->post('generate-qr/(:any)', 'VehiclesController::generateVehicleQR/$1');
    $routes->get('qr/(:any)', 'VehiclesController::getVehicleQR/$1');
    $routes->get('export-data/(:any)', 'VehiclesController::exportVehicleData/$1');
    $routes->get('export-all', 'VehiclesController::exportAllVehicles');
    
    // Photos URL endpoints
    $routes->get('photos-url/([A-Za-z0-9]{6})', 'VehiclesController::getPhotosUrl/$1');
    $routes->post('photos-url/([A-Za-z0-9]{6})', 'VehiclesController::savePhotosUrl/$1');
    

    
    // AWS S3 Integration
    $routes->get('test-s3', 'VehiclesController::testS3Config');
    $routes->get('s3-photos/(:any)', 'VehiclesController::getS3VehiclePhotos/$1');
    $routes->post('upload-s3-photos/(:any)', 'VehiclesController::uploadS3VehiclePhotos/$1');
    $routes->post('upload-s3', 'VehiclesController::uploadToS3');
    $routes->post('batch-upload-s3', 'VehiclesController::batchUploadS3');
    $routes->post('delete-s3-photo', 'VehiclesController::deleteS3Photo');
    $routes->post('delete-photos', 'VehiclesController::deletePhotos');
    
    // Duplicate photo detection
    $routes->get('detect-duplicates/(:any)', 'VehiclesController::detectDuplicatePhotos/$1');
    $routes->get('debug-duplicates/(:any)', 'VehiclesController::debugDuplicatePhotos/$1');
    $routes->post('remove-duplicates', 'VehiclesController::removeDuplicatePhotos');
    
    // Debug route for VIN testing
    $routes->get('debug/(:any)', 'VehiclesController::debugVin/$1');
    
    // Fallback for legacy numeric IDs
    $routes->get('view/(:num)', 'VehiclesController::view/$1');
    
    // VIN-based view using last 6 characters with prefix
    $routes->get('v/([A-Za-z0-9]{6})', 'VehiclesController::viewByVinLast6/$1');
    
    // Direct VIN-based view using last 6 characters (without prefix)
    $routes->get('([A-Za-z0-9]{6})', 'VehiclesController::viewByVinLast6/$1');
    });
};
