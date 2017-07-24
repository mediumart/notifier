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
        if ($this->isRegistered($channel)) {
            return;
        }

        if ((new FactorySpecification)->isSatisfiedBy($channel) ) {
            $this->channels[] = $channel;

            return;
        }
        
        throw (new SpecificationException)->exception($channel);
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
        return $this->channels;
    }

    /**
     * Check if a given channel is registered.
     * 
     * @param  string  $channel 
     * @return boolean
     */
    protected function isRegistered($channel)
    {
        return array_search($channel, $this->channels) !== false;
    }
}
