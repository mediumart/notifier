<?php

namespace Mediumart\Notifier\Support;

use Exception;
use Illuminate\Support\Str;

abstract class BulkFactory
{
     /**
     * Check for the factory capacity.
     *
     * @param  string  $driver
     * @return bool
     */
    public static function canHandleNotification($driver) {}

    /**
     * Create a new driver instance.
     *
     * @param $driver
     * @return mixed
     * 
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
