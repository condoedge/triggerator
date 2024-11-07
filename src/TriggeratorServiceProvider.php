<?php

namespace Condoedge\Triggerator;

use Condoedge\Triggerator\Facades\Actions;
use Condoedge\Triggerator\Facades\Triggers;
use Condoedge\Triggerator\Services\ActionsManager;
use Condoedge\Triggerator\Services\TriggersManager;
use Condoedge\Triggerator\Triggers\Contracts\TriggerContract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;

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

        $this->loadListeners();

        $this->loadCrons();

        Actions::setActions(config('triggerator.actions'));
        Triggers::setTriggers(config('triggerator.triggers'));
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
            Route::middleware('web')->group(__DIR__.'/../routes/web.php');
        });

        $this->app->singleton('actions-manager', function () {
            return new ActionsManager();
        });

        $this->app->singleton('triggers-manager', function () {
            return new TriggersManager();
        });

        $this->app->bind('action-model', function () {
            return new (config('triggerator.models.action'))();
        });
        
        $this->app->bind('trigger-model', function () {
            return new (config('triggerator.models.trigger'))();
        });
        
        $this->app->bind('trigger-execution-model', function () {
            return new (config('triggerator.models.trigger-execution'))();
        });
    }

    protected function loadHelpers()
    {
        $helpersDir = __DIR__.'/Helpers';

        $autoloadedHelpers = collect(File::allFiles($helpersDir))->map(fn($file) => $file->getRealPath());

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

    protected function loadListeners()
    {
        Event::listen(TriggerContract::class, \Condoedge\Triggerator\Listeners\TriggerListener::class);
    }
}
