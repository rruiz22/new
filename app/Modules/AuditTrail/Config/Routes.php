<?php

namespace Modules\AuditTrail\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * Audit Trail Module Routes
 */
return function (RouteCollection $routes) {
    // Audit Trail Module Routes
    $routes->group('audit', ['namespace' => 'Modules\AuditTrail\Controllers', 'filter' => 'session'], function($routes) {
        $routes->get('/', 'AuditController::index');
        $routes->get('analytics', 'AuditController::analytics');
        $routes->get('analytics/map-data', 'AuditController::getMapDataJson');
        $routes->get('analytics/location-stats', 'AuditController::getLocationStatsJson');
        $routes->get('(:num)', 'AuditController::show/$1');
        $routes->get('export/pdf', 'AuditController::exportPdf');
        $routes->get('export/excel', 'AuditController::exportExcel');
    });
};
