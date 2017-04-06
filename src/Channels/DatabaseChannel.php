<?php

namespace Mediumart\Notifier\Channels;

use Illuminate\Container\Container;
use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;
use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;

class DatabaseChannel extends BaseDatabaseChannel implements Factory, Dispatcher
{
    /**
     * Check for the driver capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['database']);
    }

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    public static function createDriver($driver)
    {
        return static::canHandleNotification($driver)
            ? Container::getInstance()->make(self::class)
            : null;
    }
}
