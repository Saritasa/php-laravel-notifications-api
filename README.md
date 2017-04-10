# PHP Blade Directives

Implementation of Notifications API.


## Laravel 5.x

Install the ```saritasa/laravel-notifications-api``` package:

```bash
$ composer require saritasa/laravel-notifications-api
```

Add the NotificationsApiServiceProvider in ``config/app.php``:

```php
'providers' => array(
    // ...
    Saritasa\Laravel\NotificationsApiServiceProvider::class,
)
```

## Models (DB mapping)

### NotificationType

Description of possible notification type, and if it should be on or off by default.

Mandatory fields
* id (*int*)
* name (*string*)
* default_on (*boolean*)

### NotificationSetting

User's personal setting value - if certain notification type is on or off.

Mandatory fields:
* id (*int*)
* user_id (*int*)
* notification_type_id (*int*)
* is_on (*boolean*)

## Contributing

1. Create fork
2. Checkout fork
3. Develop locally as usual
4. When ready, create pull request

## Resources

* [Bug Tracker](http://github.com/saritasa/php-blade-directives/issues)
* [Code](http://github.com/saritasa/php-blade-directives)
