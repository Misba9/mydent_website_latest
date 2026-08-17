<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Mariuzzo\LaravelJsLocalization\Commands\LangJsCommand;
use Mariuzzo\LaravelJsLocalization\Generators\LangJsGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        // Bind the Laravel JS Localization command into the app IOC.
        $this->app->singleton('localization.js', function ($app) {
            $app = $this->app;
            $laravelMajorVersion = (int) $app::VERSION;

            $files = $app['files'];

            if ($laravelMajorVersion === 4) {
                $langs = $app['path.base'].'/app/lang';
            } elseif ($laravelMajorVersion >= 5 && $laravelMajorVersion < 9) {
                $langs = $app['path.base'].'/resources/lang';
            } elseif ($laravelMajorVersion >= 9) {
                $langs = app()->langPath();
            }
            $messages = $app['config']->get('localization-js.messages');
            $generator = new LangJsGenerator($files, $langs, $messages);

            return new LangJsCommand($generator);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        Paginator::useBootstrap();

        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('products') && \Illuminate\Support\Facades\DB::table('products')->count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('model_has_roles')) {
                \Illuminate\Support\Facades\DB::statement("UPDATE model_has_roles SET model_type = 'App\\\\Models\\\\User' WHERE model_type LIKE '%\\\\\\\\%' OR model_type NOT LIKE '%\\\\%'");
            }
        } catch (\Throwable $e) {
            // Silently swallow any database boot connection errors if database is initializing
        }
    }

}
