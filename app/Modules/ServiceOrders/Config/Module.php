<?php

namespace Modules\ServiceOrders\Config;

class Module
{
    /**
     * Module name
     */
    public static string $name = 'ServiceOrders';
    
    /**
     * Module version
     */
    public static string $version = '1.0.0';
    
    /**
     * Module description
     */
    public static string $description = 'Service Orders Management Module';
    
    /**
     * Module author
     */
    public static string $author = 'Your Team';
    
    /**
     * Module dependencies
     */
    public static array $dependencies = [
        'App\\Models\\ClientModel',
        'App\\Models\\UserModel',
        'App\\Models\\SettingsModel'
    ];
    
    /**
     * Module routes file
     */
    public static string $routesFile = 'Routes.php';
    
    /**
     * Module views path (relative to module root)
     */
    public static string $viewsPath = 'Views';
    
    /**
     * Initialize module - set up view paths
     */
    public static function init()
    {
        // Add the module's view path to CodeIgniter's view paths
        $config = config('Paths');
        $viewPaths = $config->viewDirectory;
        
        // Add our module's Views directory
        if (is_array($viewPaths)) {
            $viewPaths[] = APPPATH . 'Modules/ServiceOrders/Views/';
        } else {
            $viewPaths = [
                $viewPaths,
                APPPATH . 'Modules/ServiceOrders/Views/'
            ];
        }
        
        return true;
    }
    
    /**
     * Get the full path to the module's views directory
     */
    public static function getViewsPath(): string
    {
        return APPPATH . 'Modules/ServiceOrders/Views/';
    }
    
    /**
     * Get the module namespace
     */
    public static function getNamespace(): string
    {
        return 'Modules\\ServiceOrders';
    }
} 