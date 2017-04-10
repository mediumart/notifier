# Notifier

[![Build Status](https://travis-ci.org/mediumart/notifier.svg?branch=master)](https://travis-ci.org/mediumart/notifier)

## Description

## Installation

Using composer:
```
$ composer require mediumart/notifier
```

Add the service provider in the `providers` array inside `config/app.php`
```php
[
    Mediumart\Notifier\NotifierServiceProvider::class,
]
```
## Usage

Create a public property named `$notificationsChannels` inside your `App\Providers\AppServiceProvider`.

```php
...

/**
* Notifications channels list.
*
* @var array
*/
public $notificationsChannels = [
    \Mediumart\Notifier\Channels\DefaultChannel::class,
];

...
```

## License

Mediumart Notifier is an open-sourced software licensed under the [MIT license](https://github.com/mediumart/notifier/blob/master/LICENSE.txt).