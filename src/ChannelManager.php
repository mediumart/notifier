<?php

namespace Mediumart\Notifier;

use ReflectionClass;
use InvalidArgumentException;
use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;
use Illuminate\Notifications\ChannelManager as Manager;

class ChannelManager extends Manager
{
    /**
     * Registered Channels factories.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * Register a new channel driver.
     *
     * @param  string  $channel
     * @return void
     */
    public function register($channel)
    {
        $this->isFactory($channel) || $this->throwsArgumentException($channel);

        if (array_search($channel = ltrim($channel, '\\'), $this->channels) === false) {
            $this->channels[] = $channel;
        }
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        if ($channel = $this->channelCreate($driver)) {
            return $channel;
        }

        return parent::createDriver($driver);
    }

    /**
     * Create a new channel driver.
     *
     * @param  string  $driver
     * @return null|\Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    protected function channelCreate($driver)
    {
        foreach ($this->channels as $channel) {
            if ($channel::canHandleNotification($driver)) {
                return $channel::createDriver($driver);
            }
        }

        return null;
    }

    /**
     * Get all of the registered channels.
     *
     * @return array
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * Check channel is a valid factory.
     *
     * @param  string  $channel
     * @return bool
     */
    public function isFactory($channel)
    {
        return (new ReflectionClass($channel))->implementsInterface(Factory::class);
    }

    /**
     * Invalid channel handler.
     *
     * @param  string  $channel
     * @throws InvalidArgumentException
     */
    protected function throwsArgumentException($channel)
    {
        $msg = "class [$channel] is not a valid implementation of '%s' interface.";

        throw new InvalidArgumentException(sprintf($msg, Factory::class));
    }
}
