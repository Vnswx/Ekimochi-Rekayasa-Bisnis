<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
// Path adjusted for InfinityFree: Laravel root is one level up from htdocs
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
// Path adjusted: vendor is one level up from htdocs
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
// Path adjusted: bootstrap is one level up from htdocs
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
