<?php

// Check PHP version.
$minPhpVersion = '8.3';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );

    exit($message);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Load our paths config file
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();

// Define main path constants
define('ROOTPATH', realpath($paths->projectDirectory) . DIRECTORY_SEPARATOR);
define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);

// Load Composer's autoloader
require_once ROOTPATH . 'vendor/autoload.php';

// Load environment settings
if (is_file(ROOTPATH . '.env')) {
    require_once SYSTEMPATH . 'Config/DotEnv.php';
    (new CodeIgniter\Config\DotEnv(ROOTPATH))->load();
}

// Load Common functions
require_once SYSTEMPATH . 'Common.php';

// Define ENVIRONMENT
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('CI_ENVIRONMENT', 'production'));
}

// Define CI_DEBUG
if (! defined('CI_DEBUG')) {
    define('CI_DEBUG', ENVIRONMENT !== 'production');
}

/**
 * ---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 * ---------------------------------------------------------------
 */

// Load constants
require_once APPPATH . 'Config/Constants.php';

// Load necessary framework classes for Autoloader
require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
require_once SYSTEMPATH . 'Modules/Modules.php';
require_once SYSTEMPATH . 'Autoloader/Autoloader.php';

// Load app-specific autoloader and modules configs
require_once APPPATH . 'Config/Autoload.php';
require_once APPPATH . 'Config/Modules.php';

// Load and Initialize Autoloader
$loader = CodeIgniter\Config\Services::autoloader();
$loader->initialize(new Config\Autoload(), new Config\Modules());
$loader->register();

// Grab our CodeIgniter instance
$app = CodeIgniter\Config\Services::codeigniter();
$app->initialize();
$app->setContext('web');

// Load essential helpers globally (needed for constructors)
helper('url');
helper('form');

$app->run();
