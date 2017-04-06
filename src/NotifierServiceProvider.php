<?php

namespace Mediumart\Notifier;

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
    private $notificationsChannelsProperty ='notificationsChannels';

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
        if (! is_array($channels = $this->getNotificationsChannels())) {
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
     */
    private function getNotificationsChannels()
    {
        $provider = $this->getProviderClassName();

        if (! property_exists($provider, $this->notificationsChannelsProperty) ||
            ! (new ReflectionProperty($provider, $this->notificationsChannelsProperty))->isPublic()) {
            return null;
        }

        return $this->getProvider()->{$this->notificationsChannelsProperty};
    }

    /**
     * Get the channels provider.
     *
     * @return \Illuminate\Support\ServiceProvider|null
     */
    protected function getProvider()
    {
        return $this->app->getProvider($this->getProviderClassName());
    }

    /**
     * Get Channels provider name.
     *
     * @return string
     */
    protected function getProviderClassName()
    {
        return $this->app->getNamespace().'Providers\AppServiceProvider';
    }
}
