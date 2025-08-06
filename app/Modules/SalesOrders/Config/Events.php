<?php

namespace Modules\SalesOrders\Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Router\RouteCollection;

// Sales Orders module events
Events::on('pre_system', function () {
    // Load Sales Orders routes
    $routesPath = APPPATH . 'Modules/SalesOrders/Config/Routes.php';
    if (file_exists($routesPath)) {
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routes = service('routes');
            $routesLoader($routes);
        }
    }
}); 