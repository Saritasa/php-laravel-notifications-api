<?php

namespace Saritasa\PushNotifications;

use Dingo\Api\Routing\Router;
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

        $this->registerRoutes();
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

    private function registerRoutes()
    {
        /* @var Router $router */
        $router = $this->app['api.router'];
        $router->version('v1', ['middleware' => ['api', 'api.auth']], function(Router $router) {
            $this->registerSettingsRoutes($router);
            $this->registerNotificationsRoutes($router);
        });
    }

    private function registerSettingsRoutes(Router $router)
    {
        $router->group([
            'namespace' => 'Saritasa\PushNotifications\Api'
        ], function(Router $router) {
            $router->get('settings/notifications', [
                'uses'  => 'SettingsApiController@getNotificationSettings',
                'as'    => 'settings.notifications'
            ]);

            $router->put('settings/notifications', [
                'uses'  => 'SettingsApiController@setNotificationSettings',
                'as'    => 'settings.notifications.update'
            ]);
        });
    }

    protected function registerNotificationsRoutes(Router $router)
    {

    }
}
