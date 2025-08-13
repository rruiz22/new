<?php

namespace Modules\PublicPages\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Public Pages Module Routes
 */
return function (RouteCollection $routes) {
    
    // Admin routes (protected)
    $routes->group('public_pages', [
        'namespace' => 'Modules\PublicPages\Controllers',
        'filter' => 'sessionauth'
    ], function($routes) {
        // Main admin routes
        $routes->get('/', 'PublicPagesController::index');
        $routes->get('index', 'PublicPagesController::index');
        $routes->get('create', 'PublicPagesController::create');
        $routes->post('store', 'PublicPagesController::store');
        $routes->get('edit/(:num)', 'PublicPagesController::edit/$1');
        $routes->post('update/(:num)', 'PublicPagesController::update/$1');
        $routes->post('delete', 'PublicPagesController::delete');
        $routes->get('preview/(:num)', 'PublicPagesController::preview/$1');
        $routes->get('duplicate/(:num)', 'PublicPagesController::duplicate/$1');
        
        // Analytics
        $routes->get('analytics/(:num)', 'PublicPagesController::analytics/$1');
        
        // File management
        $routes->post('upload-files', 'PublicPagesController::uploadFiles');
        $routes->post('delete-file', 'PublicPagesController::deleteFile');
        
        // Version control
        $routes->post('restore-version', 'PublicPagesController::restoreVersion');
    });

    // Public routes (no authentication required)
    $routes->group('p', [
        'namespace' => 'Modules\PublicPages\Controllers'
    ], function($routes) {
        // Main public page view
        $routes->get('(:segment)', 'PublicViewController::view/$1');
        $routes->post('(:segment)', 'PublicViewController::view/$1'); // For password forms
        
        // AJAX endpoints
        $routes->post('like', 'PublicViewController::toggleLike');
        $routes->get('content/(:segment)', 'PublicViewController::getPageContent/$1');
        
        // Social sharing
        $routes->get('share/(:segment)/(:segment)', 'PublicViewController::share/$1/$2');
        $routes->get('qr/(:segment)', 'PublicViewController::qrCode/$1');
    });

    // Additional public routes
    $routes->group('pages', [
        'namespace' => 'Modules\PublicPages\Controllers'
    ], function($routes) {
        // Page listing and search
        $routes->get('/', 'PublicViewController::pageList');
        $routes->get('list', 'PublicViewController::pageList');
        $routes->get('search', 'PublicViewController::search');
        
        // Feeds
        $routes->get('rss', 'PublicViewController::rss');
        $routes->get('sitemap.xml', 'PublicViewController::sitemap');
    });
};
