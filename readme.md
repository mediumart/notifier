# Notifier

[![Build Status](https://travis-ci.org/mediumart/notifier.svg?branch=master)](https://travis-ci.org/mediumart/notifier)
[![Latest Stable Version](https://poser.pugx.org/mediumart/notifier/v/stable)](https://packagist.org/packages/mediumart/notifier)
[![License](https://poser.pugx.org/mediumart/notifier/license)](https://packagist.org/packages/mediumart/notifier)

## Description
Laravel offers a really simple way of defining custom channels for your app notifications. You just have to return the channel class name through the `via` method of any notification: [https://laravel.com/docs/5.4/notifications#custom-channels](https://laravel.com/docs/5.4/notifications#custom-channels).

However, using the channel class name inside your notifications objects, can become cumbersome sometimes. 

This package allow you to return a custom hook name for custom notification channel, instead of the channel class name through the `via` method of any of your notifications: 

```php
/**
 * Get the notification channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */
public function via($notifiable)
{
    return ['twitter'];
}
```

Imagine that you have 10 or 30 notifications class in the same app, that need to be send through a given channel, well now each of the notifications class is tighly coupled with your custom channel class, and if you happen to change the channel class name or may be decide to use a different implementation for the same channel, you will have to open all of your 30 notifications one by one in order to update the returned class name in their `via` method, and that can be very tedious.

And frankly, i find it more exciting and really cool to be able to define a custom hook name, sort of like built in notifications channels like 'mail', or 'slack', or 'database', or whatever...

So if you do like the idea, let's get started!

## Installation

Using composer:
```
$ composer require mediumart/notifier
```

If you are using laravel 5 prior to version 5.5, add the service provider in the `providers` array inside `config/app.php`
```php
Mediumart\Notifier\NotifierServiceProvider::class
```
## Usage

You need a class that will act as factory for your custom channel. This factory class can be the custom channel class itself if you want, it just need to implement the `Mediumart\Notifier\Contracts\Channels\Factory` interface which declare two static methods: `canHandleNotification` and `createDriver`. Both methods receive as their only argument the driver hook name that is to be created.

The first method `canHandleNotification` should return a Boolean(`true` or `false`) to indicate whether or not the factory is able to create the appropriate driver for the notification.

```php
/**
 * Check for the factory capacity.
 *
 * @param  string $driver
 * @return bool
 */
public static function canHandleNotification($driver)
{
    return in_array($driver, ['twitter']);
}
```

The second method `createDriver` will be called by the `ChannelManager` if the first one has returned `true` on this factory, and therefore, should return a fully resolved instance of the appropriate driver to use.

```php
/**
 * Create a new driver instance.
 *
 * @param  $driver
 * @return mixed|\Mediumart\Notifier\Contracts\Channels\Dispatcher
 */
public static function createDriver($driver)
{
    return static::canHandleNotification($driver) 
        ? new static(App::make('someTwitterClient')) : null;
}
```

Now that you have a fully functionnal factory, you need to register it with your application, the easiest way to do that is to create(if not already exists) a **public** property of type `array`, named `$notificationsChannels` inside your `App\Providers\AppServiceProvider` and list your factory class name in there.

```php
/**
* Notifications channels list.
*
* @var array
*/
public $notificationsChannels = [
    \Mediumart\Notifier\Channels\TwitterChannel::class,
];
```

## License

Mediumart Notifier is an open-sourced software licensed under the [MIT license](https://github.com/mediumart/notifier/blob/master/LICENSE.txt).