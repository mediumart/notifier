<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $notificationsChannels = [
        \Mediumart\Notifier\Channels\DefaultChannel::class
    ];
}

use Mediumart\Notifier\NotifierServiceProvider;

class NotifierExtendedServiceProvider extends NotifierServiceProvider
{
    protected $notificationsChannels = [
        \Mediumart\Notifier\Channels\BroadcastChannel::class
    ];
}

class NullServiceProvider extends NotifierServiceProvider
{
}

class ProtectedServiceProviderProperty
{
    protected $notificationsChannels = [] ;
}

namespace Tests\Unit;

use Tests\TestCase;
use RuntimeException;
use App\Providers\NullServiceProvider;
use Mediumart\Notifier\ChannelManager;
use Mediumart\Notifier\NotifierServiceProvider;
use App\Providers\ProtectedServiceProviderProperty;

class NotifierServiceProviderTest extends TestCase
{
    public function testRegisterNotificationsChannels()
    {
        $manager = $this->app->make(Channelmanager::class);
        $this->assertSame([
            \Mediumart\Notifier\Channels\DefaultChannel::class,
            \Mediumart\Notifier\Channels\BroadcastChannel::class
        ], $manager->getChannels());
    }

    public function testGetNotificationsChannels()
    {
        $notifier = new NullServiceProvider($this->app);
        $notifier->setProviderName(NullServiceProvider::class);

        $this->assertNull($notifier->getNotificationsChannels());
    }

    public function testGetNotificationsChannelsThrowsRuntimeException()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(ProtectedServiceProviderProperty::class);

        $this->expectException(RuntimeException::class);
        $notifier->getNotificationsChannels();
    }
}
