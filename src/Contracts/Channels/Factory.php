<?php

namespace Mediumart\Notifier\Contracts\Channels;

Interface Factory
{
    /**
     * Check for the driver capacity.
     *
     * @param  string  $driver
     * @return bool
     */
    public static function canHandleNotification($driver);

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    public static function createDriver($driver);
}
