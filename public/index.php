<?php

define('PUBLIC_PATH', __DIR__);
define('BASE_PATH', __DIR__ . '/../');
define('VERSION', '3.4.2');

use App\Services\Boot;

// Vendor Autoload
require BASE_PATH . '/vendor/autoload.php';

error_reporting(0);

Boot::loadEnv();
Boot::setDebug();
Boot::setVersion(VERSION);
// config time zone
Boot::setTimezone();
// Init db
Boot::bootDb();

// Build Slim App
$app = require BASE_PATH . '/app/routes.php';

// Run ButterFly!
$app->run();
