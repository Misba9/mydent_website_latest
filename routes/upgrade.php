<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// HTTP upgrade/migrate endpoints are disabled in production.
// Use CLI / deploy.sh for migrations and seeders.
if (app()->environment('production')) {
    return;
}

Route::middleware(['auth', 'permission:manage_admin_dashboard'])->group(function () {
    // upgrade to v3.0.0
    Route::get('/upgrade-to-v3-0-0', function () {
        Artisan::call('db:seed', ['--class' => 'DefaultPaymentGatewaySeeder']);
    });

    Route::get('upgrade/database', function () {
        Artisan::call('migrate', ['--force' => true]);
    });

    Route::get('lang-js', function () {
        Artisan::call('lang:js');
    });
});
