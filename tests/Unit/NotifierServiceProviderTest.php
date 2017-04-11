<?php

namespace Notifier\Tests\Unit;

use RuntimeException;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
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
    }

    public function test_register_notifications_channels_class_does_not_exists()
    {
        $manager = $this->app->make(ChannelManager::class);
        $notifier = \Mockery::mock('Mediumart\Notifier\NotifierServiceProvider[getNotificationsChannels]', [$this->app]);

        $notifier->shouldReceive('getNotificationsChannels')->andReturn(['Class\That\Does\Not\exists']);
        $notifier->registerNotificationsChannels();
        $this->assertEmpty($manager->getChannels());
    }

    public function test_get_self_contained_notifications_channels_property()
    {
        $notifier  = new NotifierServiceProviderTest_ExtendedNotifierServiceProvider($this->app);
        $this->assertSame(['Dummy\Extended\TestChannel'], $notifier->getNotificationsChannels());
    }

    public function test_get_notifications_channels_property_from_another_registered_service_provider()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_AppServiceProvider::class);
        $this->assertSame(['Dummy\TestChannel'],  $notifier->getNotificationsChannels());
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

    public function test_notification_facade_proxy()
    {
        $this->assertInstanceOf(ChannelManager::class, Notification::getFacadeRoot());
    }
}

class NotifierServiceProviderTest_AppServiceProvider extends ServiceProvider
{
    public $notificationsChannels = [
        'Dummy\TestChannel'
    ];
}

class NotifierServiceProviderTest_ExtendedNotifierServiceProvider extends NotifierServiceProvider
{
    protected $notificationsChannels = [
        'Dummy\Extended\TestChannel'
    ];
}

class NotifierServiceProviderTest_ExceptionServiceProvider
{
    protected $notificationsChannels = [] ;
}

class NotifierServiceProviderTest_NullServiceProvider
{
}
