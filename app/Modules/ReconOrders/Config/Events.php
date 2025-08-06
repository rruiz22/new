<?php

namespace Modules\ReconOrders\Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Router\RouteCollection;

// Recon Orders module events
Events::on('pre_system', function () {
    // Log that we're loading ReconOrders routes
    log_message('info', 'ReconOrders: Loading module routes');
    
    // Load Recon Orders routes
    $routesPath = APPPATH . 'Modules/ReconOrders/Config/Routes.php';
    if (file_exists($routesPath)) {
        log_message('info', 'ReconOrders: Routes file exists at ' . $routesPath);
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routes = service('routes');
            $routesLoader($routes);
            log_message('info', 'ReconOrders: Routes loaded successfully');
        } else {
            log_message('error', 'ReconOrders: Routes loader is not callable');
        }
    } else {
        log_message('error', 'ReconOrders: Routes file not found at ' . $routesPath);
    }
}); 