<?php

namespace Modules\GetReady\Config;

use CodeIgniter\Modules\ModuleConfig;

class Module extends ModuleConfig
{
    /**
     * The module's name
     */
    public string $name = 'GetReady';

    /**
     * Description of the module
     */
    public string $description = 'Vehicle Get Ready tracking and workflow management system';

    /**
     * Version of the module
     */
    public string $version = '1.0.0';

    /**
     * Author information
     */
    public array $author = [
        'name' => 'MDA Team',
        'email' => 'support@mydetailarea.com',
    ];

    /**
     * Dependencies required by this module
     */
    public array $dependencies = [
        'SalesOrders',
        'Vehicles',
    ];

    /**
     * Module permissions
     */
    public array $permissions = [
        'get_ready.view',
        'get_ready.create',
        'get_ready.edit',
        'get_ready.delete',
        'get_ready.manage_steps',
        'get_ready.assign_tech',
        'get_ready.manage_all',
    ];

    /**
     * Auto-discovery configuration
     */
    public bool $enabled = true;
}