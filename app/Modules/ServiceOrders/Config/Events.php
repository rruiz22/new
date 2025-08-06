<?php

namespace Modules\ServiceOrders\Config;

use CodeIgniter\Events\Events;

// Service Orders module events
Events::on('post_system', function () {
    // Load Service Orders routes
    $routesPath = APPPATH . 'Modules/ServiceOrders/Config/Routes.php';
    if (file_exists($routesPath)) {
        $routes = service('routes');
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routesLoader($routes);
        }
    }
}); 