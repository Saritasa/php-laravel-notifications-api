<?php

namespace Saritasa\PushNotifications;

use Illuminate\Support\ServiceProvider;

class NotificationsApiServiceProvider extends ServiceProvider
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
        if ($this->app->runningInConsole()) {
            $this->declarePublishedConfig();
            $this->declarePublishedArtifacts();
            $this->declarePublishedMigrations();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    private function declarePublishedConfig()
    {
        $this->publishes([
            __DIR__.'/../config/push.php' => config_path('push.php')
        ], 'config');
    }

    private function declarePublishedArtifacts()
    {
        $this->publishes([
            __DIR__.'/../Artifacts/API' => base_path('Artifacts/API')
        ], 'swagger');
    }

    private function declarePublishedMigrations()
    {
        $this->publishes([
            __DIR__.'/../database' => database_path()
        ], 'migrations');
    }
}
