<?php

namespace Tests\Unit;

use Tests\TestCase;
use RuntimeException;
use Mediumart\Notifier\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Mediumart\Notifier\NotifierServiceProvider;

class NotifierServiceProviderTest extends TestCase
{
    public function test_register_notifications_channels()
    {
        $manager = $this->app->make(ChannelManager::class);
        $notifier = \Mockery::mock('Mediumart\Notifier\NotifierServiceProvider[getNotificationsChannels]', [$this->app]);

        $notifier->shouldReceive('getNotificationsChannels')->andReturn('not\an\array');
        $notifier->registerNotificationsChannels();
        $this->assertEmpty($manager->getChannels());

        $notifier->shouldReceive('getNotificationsChannels')->andReturn(['Class\That\Does\Not\exists']);
        $notifier->registerNotificationsChannels();
        $this->assertEmpty($manager->getChannels());
    }

    public function test_get_self_contained_notifications_channels_property()
    {
        $notifier  = new NotifierServiceProviderTest_ExtendedNotifierServiceProvider($this->app);
        $this->assertSame(['Mediumart\Notifier\Channels\BroadcastChannel'], $notifier->getNotificationsChannels());
    }

    public function test_get_notifications_channels_property_from_another_registered_service_provider()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_AppServiceProvider::class);
        $this->assertSame(['Mediumart\Notifier\Channels\DefaultChannel'],  $notifier->getNotificationsChannels());
    }

    public function test_get_notifications_channels_return_null_if_property_not_exists()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_NullServiceProvider::class);
        $this->assertNull($notifier->getNotificationsChannels());
    }

    public function test_get_notifications_channels_throws_runtime_exception()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_ExceptionServiceProvider::class);

        $this->expectException(RuntimeException::class);
        $notifier->getNotificationsChannels();
    }
}

class NotifierServiceProviderTest_AppServiceProvider extends ServiceProvider
{
    public $notificationsChannels = [
        'Mediumart\Notifier\Channels\DefaultChannel'
    ];
}

class NotifierServiceProviderTest_ExtendedNotifierServiceProvider extends NotifierServiceProvider
{
    protected $notificationsChannels = [
        'Mediumart\Notifier\Channels\BroadcastChannel'
    ];
}

class NotifierServiceProviderTest_ExceptionServiceProvider
{
    protected $notificationsChannels = [] ;
}

class NotifierServiceProviderTest_NullServiceProvider
{
}
