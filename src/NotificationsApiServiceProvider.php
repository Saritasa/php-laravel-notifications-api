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
     * Bootstrap the application events.
     *
     * @param Router $apiRouter Dingo/Api router
     * @return void
     */
    public function boot(Router $apiRouter)
    {
        if ($this->app->runningInConsole()) {
            $this->declarePublishedFiles();
        }

        $this->registerRoutes($apiRouter);
    }

    /**
     * Register API routes and handlers for them

     * @param Router $apiRouter Dingo/Api router
     * @return void
     */
    protected function registerRoutes(Router $apiRouter)
    {
        /* @var Router $router */
        $apiRouter->version('v1', [
            'middleware' => ['api', 'api.auth'],
            'namespace' => 'Saritasa\PushNotifications\Api'
        ], function (Router $apiRouter) {
            $this->registerSettingsRoutes($apiRouter);
            $this->registerNotificationsRoutes($apiRouter);
        });
    }

    protected function registerSettingsRoutes(Router $apiRouter)
    {
        $apiRouter->group([ 'prefix' => 'settings' ], function (Router $api) {

            $api->get('notifications', [
                'uses'  => 'SettingsApiController@getNotificationSettings',
                'as'    => 'settings.notifications'
            ]);

            $api->put('notifications', [
                'uses'  => 'SettingsApiController@setNotificationSettings',
                'as'    => 'settings.notifications.update'
            ]);

            $api->put('device', [
                'uses'  => 'SettingsApiController@saveUserDevice',
                'as'    => 'settings.device'
            ]);
        });
    }

    protected function registerNotificationsRoutes(Router $router)
    {
        $router->group(['prefix' => 'notifications'], function (Router $api) {

            $api->get('', [
                'uses'  => 'NotificationsApiController@getUserNotifications',
                'as'    => 'notifications'
            ]);

            $api->put('viewed', [
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
            __DIR__ . '/../docs/API' => base_path('docs/API')
        ], 'swagger');
    }

    private function declarePublishedMigrations()
    {
        $this->publishes([
            __DIR__.'/../database' => database_path()
        ], 'migrations');
    }
}
