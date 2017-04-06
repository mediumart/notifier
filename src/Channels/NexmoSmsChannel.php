<?php

namespace Mediumart\Notifier\Channels;

use Nexmo\Client as NexmoClient;
use Illuminate\Container\Container;
use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;
use Nexmo\Client\Credentials\Basic as NexmoCredentials;
use Illuminate\Notifications\Channels\NexmoSmsChannel as BaseNexmoSmsChannel;

class NexmoSmsChannel extends BaseNexmoSmsChannel implements Factory, Dispatcher
{
    /**
     * Check for the driver capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['nexmo']);
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

        return new static(
            new NexmoClient(new NexmoCredentials(
                $app['config']['services.nexmo.key'],
                $app['config']['services.nexmo.secret']
            )),
            $app['config']['services.nexmo.sms_from']
        );
    }
}
