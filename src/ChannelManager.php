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
     * Register a new channel factory.
     *
     * @param  string  $channel
     * @return void
     */
    public function register($channel)
    {
        $this->channels[] = $channel;
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
        if ($channel = $this->createChannel($driver)) {
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
    protected function createChannel($driver)
    {
        foreach ($this->getChannels() as $channel) {
            if (! (new FactorySpecification)->isSatisfiedBy($channel)) {
                throw (new SpecificationException)->exception($channel);
            }

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
        return $this->channels = array_unique($this->channels);
    }
}
