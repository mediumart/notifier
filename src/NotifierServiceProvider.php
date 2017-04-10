<?php

namespace Mediumart\Notifier;

use RuntimeException;
use ReflectionProperty;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Notifications\Factory as FactoryContract;
use Illuminate\Contracts\Notifications\Dispatcher as DispatcherContract;

class NotifierServiceProvider extends ServiceProvider
{
    /**
     * Notifications channels property name.
     *
     * @var string
     */
    protected $notificationsChannelsProperty ='notificationsChannels';

    /**
     * @var string
     */
    protected $providerName;

    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNotificationsChannels();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager($app);
        });

        $this->app->alias(
            ChannelManager::class, DispatcherContract::class
        );

        $this->app->alias(
            ChannelManager::class, FactoryContract::class
        );
    }

    /**
     * Register Notifications channels.
     *
     * @return void
     */
    public function registerNotificationsChannels()
    {
        if (! is_array($channels = $this->getNotificationsChannels())
            || empty($channels)) {
            return;
        }

        $manager = $this->app->make(ChannelManager::class);

        foreach ($channels as $channel) {
            if (class_exists($channel)) {
                $manager->register($channel);
            }
        }
    }

    /**
     * Get list of declared channels.
     *
     * @return null|array
     * @throws RuntimeException
     */
    public function getNotificationsChannels()
    {
        if (property_exists($this, $this->notificationsChannelsProperty)) {
            return $this->{$this->notificationsChannelsProperty};
        }

        $provider = $this->getProviderName();

        if (! property_exists($provider, $this->notificationsChannelsProperty)) {
            return null;
        }

        if (! (new ReflectionProperty($provider, $this->notificationsChannelsProperty))->isPublic()) {
            throw new RuntimeException(
                sprintf("The visibility of property [%s] in class [%s] should be 'public'",
                    $this->notificationsChannelsProperty,
                    $provider
                )
            );
        }

        return $this->getProvider($provider)->{$this->notificationsChannelsProperty};
    }

    /**
     * Get the channels provider.
     *
     * @param  string  $name
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($name = null)
    {
        $name = $name ?: $this->getProviderName();

        return $this->app->getProvider($name);
    }

    /**
     * Get Channels provider name.
     *
     * @return string
     */
    public function getProviderName()
    {
        if (! $this->providerName) {
            return $this->providerName = $this->defaultProviderName();
        }

        return $this->providerName;
    }

    /**
     * Set teh channels provider name.
     *
     * @param $name
     */
    public function setProviderName($name)
    {
        $this->providerName = !empty($name) ? $name : null;
    }

    /**
     * Get default channels provider name.
     *
     * @return string
     */
    public function defaultProviderName()
    {
        return $this->app->getNamespace().'Providers\AppServiceProvider';
    }
}
