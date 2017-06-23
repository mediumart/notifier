<?php

namespace Mediumart\Notifier\Support;

use Exception;
use Illuminate\Support\Str;
use Mediumart\Notifier\Contracts\Channels\Factory;

abstract class BulkFactory implements Factory
{
    /**
     * Create a new driver instance.
     *
     * @param $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher|null
     * @throws \Exception
     */
    final public static function createDriver($driver)
    {
        if (! static::canHandleNotification($driver)) {
            return null;
        }

        $method = 'create'.Str::studly($driver).'Driver';

        if (method_exists($factory = app(static::class), $method)) {
            return $factory->$method($driver);
        }

        throw new Exception("Method [$method] not found on factory class: ".static::class);
    }
}
