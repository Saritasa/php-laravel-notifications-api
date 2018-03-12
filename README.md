# PHP Laravel Notifications API

[![Build Status](https://travis-ci.org/Saritasa/php-laravel-notifications-api.svg?branch=master)](https://travis-ci.org/Saritasa/php-laravel-notifications-api)
[![Release](https://img.shields.io/github/release/saritasa/php-laravel-notifications-api.svg)](https://github.com/Saritasa/php-laravel-notifications-api/releases)
[![PHPv](https://img.shields.io/packagist/php-v/saritasa/laravel-notifications-api.svg)](http://www.php.net)
[![Downloads](https://img.shields.io/packagist/dt/saritasa/laravel-notifications-api.svg)](https://packagist.org/packages/saritasa/laravel-notifications-api)

Implementation of Notifications API.


## Laravel 5.x

Install the ```saritasa/laravel-notifications-api``` package:

```bash
$ composer require saritasa/laravel-notifications-api
```

If you use Laravel 5.4 or less,
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

1. Create fork, checkout it
2. Develop locally as usual. **Code must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/)** -
    run [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to ensure, that code follows style guides
3. **Cover added functionality with unit tests** and run [PHPUnit](https://phpunit.de/) to make sure, that all tests pass
4. Update [README.md](README.md) to describe new or changed functionality
5. Add changes description to [CHANGES.md](CHANGES.md) file. Use [Semantic Versioning](https://semver.org/) convention to determine next version number.
6. When ready, create pull request

### Make shortcuts

If you have [GNU Make](https://www.gnu.org/software/make/) installed, you can use following shortcuts:

* ```make cs``` (instead of ```php vendor/bin/phpcs```) -
    run static code analysis with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    to check code style
* ```make csfix``` (instead of ```php vendor/bin/phpcbf```) -
    fix code style violations with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    automatically, where possible (ex. PSR-2 code formatting violations)
* ```make test``` (instead of ```php vendor/bin/phpunit```) -
    run tests with [PHPUnit](https://phpunit.de/)
* ```make install``` - instead of ```composer install```
* ```make all``` or just ```make``` without parameters -
    invokes described above **install**, **cs**, **test** tasks sequentially -
    project will be assembled, checked with linter and tested with one single command

## Resources

* [Bug Tracker](http://github.com/saritasa/php-laravel-notifications-api/issues)
* [Code](http://github.com/saritasa/php-laravel-notifications-api)
* [Changes History](CHANGES.md)
* [Authors](http://github.com/saritasa/php-laravel-notifications-api/contributors)
