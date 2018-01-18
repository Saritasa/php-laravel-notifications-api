# PHP Laravel Notifications API

[![Build Status](https://travis-ci.org/Saritasa/php-laravel-notifications-api.svg?branch=master)](https://travis-ci.org/Saritasa/php-laravel-notifications-api)

Implementation of Notifications API.


## Laravel 5.x

Install the ```saritasa/laravel-notifications-api``` package:

```bash
$ composer require saritasa/laravel-notifications-api
```

If you use Laraval 5.4 or less,
or 5.5+ with [package discovery](https://laravel.com/docs/5.5/packages#package-discovery) disabled,
add the NotificationsApiServiceProvider in ``config/app.php``:

```php
'providers' => array(
    // ...
    Saritasa\PushNotifications\NotificationsApiServiceProvider::class,
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
3. Develop locally as usual. **Code must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/)**
4. Run [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to ensure, that code follows style guides
5. Update [README.md](README.md) to describe new or changed functionality. Add changes description to [CHANGES.md](CHANGES.md) file.
6. When ready, create pull request

## Resources

* [Bug Tracker](http://github.com/saritasa/php-laravel-notifications-api/issues)
* [Code](http://github.com/saritasa/php-laravel-notifications-api)
* [Changes History](CHANGES.md)
* [Authors](http://github.com/saritasa/php-laravel-notifications-api/contributors)
