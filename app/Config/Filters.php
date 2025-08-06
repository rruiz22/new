<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Shield\Filters\AuthRates;
use CodeIgniter\Shield\Filters\AuthSession;
use CodeIgniter\Shield\Filters\ChainAuth;
use CodeIgniter\Shield\Filters\ForcePasswordReset;
use CodeIgniter\Shield\Filters\GroupFilter;
use CodeIgniter\Shield\Filters\PermissionFilter;
use CodeIgniter\Shield\Filters\RoleFilter as ShieldRoleFilter;
use CodeIgniter\Shield\Filters\TokenAuth;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     *
     * [filter_name => classname]
     * or [filter_name => [classname1, classname2, ...]]
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        // Shield filters
        'session'     => AuthSession::class,
        'tokens'      => TokenAuth::class,
        'chain'       => ChainAuth::class,
        'auth-rates'  => AuthRates::class,
        'group'       => GroupFilter::class,
        'permission'  => PermissionFilter::class,
        'role'        => RoleFilter::class,
        'force-reset' => ForcePasswordReset::class,
        'checklang'   => \App\Filters\LanguageFilter::class,
        'auth'        => \App\Filters\AuthFilter::class,
        'moduleauth'  => \App\Filters\ModuleAuthFilter::class,
        'sessionauth' => \App\Filters\SessionAuthFilter::class,
    ];

    /**
     * List of special required filters.
     *
     * The filters listed here are special. They are applied before and after
     * other kinds of filters, and always applied even if a route does not exist.
     *
     * Filters set by default provide framework functionality. If removed,
     * those functions will no longer work.
     *
     * @see https://codeigniter.com/user_guide/incoming/filters.html#provided-filters
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            'toolbar',     // Debug Toolbar
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // Temporalmente desactivado para depuración
            // 'csrf',
            // 'invalidchars',
            'checklang',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'POST' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        'session' => [
            'before' => [
                'admin/*',  // Protege toda la sección de administración
                'profile/*', // Protege los perfiles de usuario
                'dashboard', // Protege el dashboard
                'chat/*',   // Protege todas las rutas del chat
                'service_orders/*', // Protege todas las rutas de Service Orders
                'sales_orders/*',   // Protege todas las rutas de Sales Orders
            ],
        ],
        'force-reset' => [
            'before' => [
                'admin/*',
                'profile/*',
                'dashboard',
                'chat/*',   // Agrega chat también al force-reset
                'service_orders/*', // Protege todas las rutas de Service Orders
                'sales_orders/*',   // Protege todas las rutas de Sales Orders
            ],
        ],
        'csrf' => [
            'except' => [
                'settings/testSmtp',
                'settings/sendTestEmail',
                '/settings/testSmtp',
                '/settings/sendTestEmail',
                'language/*'
            ]
        ],
    ];
}
