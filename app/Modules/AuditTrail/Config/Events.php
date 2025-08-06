<?php

namespace Modules\AuditTrail\Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Router\RouteCollection;

// Audit Trail module events
Events::on('pre_system', function () {
    // Load Audit Trail routes
    $routesPath = APPPATH . 'Modules/AuditTrail/Config/Routes.php';
    if (file_exists($routesPath)) {
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routes = service('routes');
            $routesLoader($routes);
        }
    }
}); 