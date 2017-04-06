<?php

namespace Mediumart\Notifier\Channels;

use Illuminate\Mail\Markdown;
use Illuminate\Container\Container;
use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;
use Illuminate\Notifications\Channels\MailChannel as BaseMailChannel;

class MailChannel extends BaseMailChannel implements Factory, Dispatcher
{
    /**
     * Check for the driver capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['mail']);
    }

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    public static function createDriver($driver)
    {
        if (! static::canHandleNotification($driver)) {
            return null;
        }

        $app = Container::getInstance();

        return $app->make(self::class)->setMarkdownResolver(function () use ($app) {
            return $app->make(Markdown::class);
        });
    }
}
