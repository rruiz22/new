<?php

namespace Modules\PublicPages\Config;

use CodeIgniter\Events\Events;

// Public Pages module events
Events::on('pre_system', function () {
    // Load Public Pages routes
    $routesPath = APPPATH . 'Modules/PublicPages/Config/Routes.php';
    if (file_exists($routesPath)) {
        $routes = service('routes');
        $routesLoader = include $routesPath;
        if (is_callable($routesLoader)) {
            $routesLoader($routes);
        }
    }
});
