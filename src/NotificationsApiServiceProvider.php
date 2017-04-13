<?php

namespace Saritasa\PushNotifications;

use Dingo\Api\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Register URLs, handed by this package
 * and declare artifacts, that can be published to application (config, DB migrations, swagger definitions)
 */
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
            $this->declarePublishedFiles();
        }

        $this->registerRoutes();
    }

    /**
     * Register API routes and handlers for them
     */
    protected function registerRoutes()
    {
        /* @var Router $router */
        $router = $this->app['api.router'];
        $router->version('v1', [
            'middleware' => ['api'],
            'namespace' => 'Saritasa\PushNotifications\Api'
        ], function(Router $router) {
            $this->registerSettingsRoutes($router);
            $this->registerNotificationsRoutes($router);
        });
    }

    protected function registerSettingsRoutes(Router $router)
    {
        $router->group([ 'prefix' => 'settings' ], function(Router $router) {

            $router->get('notifications', [
                'uses'  => 'SettingsApiController@getNotificationSettings',
                'as'    => 'settings.notifications'
            ]);

            $router->put('notifications', [
                'uses'  => 'SettingsApiController@setNotificationSettings',
                'as'    => 'settings.notifications.update'
            ]);

        });
    }

    protected function registerNotificationsRoutes(Router $router)
    {
        $router->group(['prefix' => 'notifications'], function (Router $router) {

            $router->get('', [
                'uses'  => 'NotificationsApiController@getUserNotifications',
                'as'    => 'notifications'
            ]);

            $router->put('viewed', [
                'uses'  => 'NotificationsApiController@markNotificationsAsViewed',
                'as'    => 'notifications.viewed'
            ]);

        });
    }

    /**
     * Declare which files can be published and customized in main application:
     * config, swagger declarations, DB migrations
     */
    protected function declarePublishedFiles()
    {
        $this->declarePublishedConfig();
        $this->declarePublishedArtifacts();
        $this->declarePublishedMigrations();
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
