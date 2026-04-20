<?php

namespace Config;

use CodeIgniter\Config\Routing as BaseRouting;

/**
 * Routing configuration
 */
class Routing extends BaseRouting
{
    /**
     * An array of files that contain route definitions.
     *
     * @var list<string>
     */
    public array $routeFiles = [
        APPPATH . 'Config/Routes.php',
    ];

    /**
     * The default namespace for Controllers.
     */
    public string $defaultNamespace = 'App\\Controllers';

    /**
     * The default controller.
     */
    public string $defaultController = 'ConUserHome';

    /**
     * The default method.
     */
    public string $defaultMethod = 'index';

    /**
     * Whether to translate dashes in URIs to underscores.
     */
    public bool $translateURIDashes = false;

    /**
     * Sets the class/method that should be called if routing doesn't find a match.
     */
    public ?string $override404 = null;

    /**
     * If TRUE, will attempt to match the URI against Controllers automatically.
     */
    public bool $autoRoute = false;

    /**
     * If TRUE, will enable the use of the 'prioritize' option when defining routes.
     */
    public bool $prioritize = false;

    /**
     * Map of URI segments and namespaces for Auto Routing (Improved).
     *
     * @var array<string, string>
     */
    public array $moduleRoutes = [];
}
