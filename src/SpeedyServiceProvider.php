<?php
namespace Rolice\Speedy;

use Illuminate\Support\ServiceProvider;

/**
 * SpeedyServiceProvider for Laravel
 *
 * @package    Rolice\Speedy
 */
class SpeedyServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/speedy.php' => config_path('speedy.php')], 'config');
        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'migrations');

        $this->mergeConfigFrom(__DIR__ . '/../config/speedy.php', 'speedy');

        $this->loadTranslationsFrom($this->app->basePath(). '/vendor/rolice/speedy/resources/lang', 'speedy');

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('speedy', function () {
            return new Speedy;
        });

//        $this->app['sync'] = $this->app->share(function ($app) {
//            return new Sync;
//        });

//        $this->commands('sync');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['speedy'];
    }
}