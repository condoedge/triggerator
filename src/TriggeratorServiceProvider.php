<?php

namespace Condoedge\Triggerator;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class TriggeratorServiceProvider extends ServiceProvider
{
    use \Kompo\Routing\Mixins\ExtendsRoutingTrait;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadHelpers();

        $this->extendRouting();

        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'triggerator');

        //Usage: php artisan vendor:publish --tag="triggerator-config"
        $this->publishes([
            __DIR__.'/../config/triggerator.php' => config_path('triggerator.php'),
        ], 'triggerator-config');

        $this->loadConfig();

        $this->loadCrons();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Best way to load routes. This ensures loading at the very end (after fortifies' routes for ex.)
        $this->booted(function () {
            \Route::middleware('web')->group(__DIR__.'/../routes/web.php');
        });
    }

    protected function loadHelpers()
    {
        $helpersDir = __DIR__.'/Helpers';

        $autoloadedHelpers = collect(\File::allFiles($helpersDir))->map(fn($file) => $file->getRealPath());

        $autoloadedHelpers->each(function ($path) {
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    protected function loadConfig()
    {
        $dirs = [
            'triggerator' => __DIR__.'/../config/triggerator.php',
        ];

        foreach ($dirs as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }

    protected function loadCrons()
    {
        $schedule = $this->app->make(Schedule::class);
    }
}
