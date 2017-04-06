<?php

namespace Mediumart\Notifier\Channels;

use Illuminate\Support\Str;
use Mediumart\Notifier\Contracts\Channels\Factory;

class DefaultChannel implements Factory
{
    /**
     * Check for the driver capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['mail', 'nexmo', 'database', 'slack', 'broadcast']);
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

        $method = 'create'.Str::studly($driver).'Driver';

        if (method_exists($factory = new static, $method)) {
            $channel = $factory->$method($driver);
        }

        return $channel ?: null;
    }

    /**
     * Create an instance of the database driver.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function createDatabaseDriver($driver)
    {
        return DatabaseChannel::createDriver($driver);
    }

    /**
     * Create an instance of the broadcast driver.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function createBroadcastDriver($driver)
    {
        return BroadcastChannel::createDriver($driver);
    }

    /**
     * Create an instance of the mail driver.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function createMailDriver($driver)
    {
        return MailChannel::createDriver($driver);
    }

    /**
     * Create an instance of the Nexmo driver.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function createNexmoDriver($driver)
    {
        return NexmoSmsChannel::createDriver($driver);
    }

    /**
     * Create an instance of the Slack driver.
     *
     * @param  $driver
     * @return \Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function createSlackDriver($driver)
    {
        return SlackWebhookChannel::createDriver($driver);
    }
}
