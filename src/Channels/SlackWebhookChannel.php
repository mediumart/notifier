<?php

namespace Mediumart\Notifier\Channels;

use GuzzleHttp\Client as HttpClient;
use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;
use Illuminate\Notifications\Channels\SlackWebhookChannel as BaseSlackWebhookChannel;

class SlackWebhookChannel extends BaseSlackWebhookChannel implements Factory, Dispatcher
{
    /**
     * Check for the driver capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['slack']);
    }

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    public static function createDriver($driver)
    {
        return static::canHandleNotification($driver) ? new static(new HttpClient) : null;
    }
}
