<?php

namespace Mediumart\Notifier;

use Illuminate\Notifications\ChannelManager as Manager;
use Mediumart\Notifier\Exception\SpecificationException;

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
     *
     * @throws  SpecificationException
     */
    public function register($channel)
    {
        if (! (new FactorySpecification)->isSatisfiedBy($channel) ) {
            throw (new SpecificationException)->exception($channel);
        }

        $this->channels[] = ltrim($channel, '\\');
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     * 
     * @throws \InvalidArgumentException
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
     * @return mixed
     */
    protected function channelCreate($driver)
    {
        foreach ($this->getChannels() as $channel) {
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
        return array_unique($this->channels);
    }
}
