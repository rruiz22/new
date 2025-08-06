<?php

namespace Modules\CarWash\Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Router\RouteCollection;

// Car Wash module events
Events::on('pre_system', function () {
    // Load Car Wash routes
    $routesPath = APPPATH . 'Modules/CarWash/Config/Routes.php';
    if (file_exists($routesPath)) {
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routes = service('routes');
            $routesLoader($routes);
        }
    }
}); 