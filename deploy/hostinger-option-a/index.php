<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Hostinger document root bootstrap
|--------------------------------------------------------------------------
|
| This file belongs in:  /home/USERNAME/public_html/index.php
| Laravel app lives in:  /home/USERNAME/mydent_app_new/
|
*/

$appBase = __DIR__.'/../mydent_app_new';

if (file_exists($maintenance = $appBase.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

$configCache = $appBase.'/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    $content = @file_get_contents($configCache);
    if ($content && (
        str_contains($content, '/Volumes/storage/') ||
        (str_contains($content, 'database.sqlite') && ! file_exists($appBase.'/database/database.sqlite'))
    )) {
        @unlink($configCache);
    }
}

require $appBase.'/vendor/autoload.php';

$app = require_once $appBase.'/bootstrap/app.php';

$app->usePublicPath(__DIR__);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
