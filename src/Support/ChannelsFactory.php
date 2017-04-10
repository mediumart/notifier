<?php

namespace Mediumart\Notifier\Support;

use Illuminate\Support\Str;
use Mediumart\Notifier\Contracts\Channels\Factory;

abstract class ChannelsFactory implements Factory
{
    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return null|\Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    final public static function createDriver($driver)
    {
        if (! static::canHandleNotification($driver)) {
            return null;
        }

        $method = 'create'.Str::studly($driver).'Driver';

        if (method_exists($factory = app(static::class), $method)) {
            $channel = $factory->$method($driver);
        }

        return $channel ?: null;
    }
}
