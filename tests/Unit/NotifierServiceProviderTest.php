<?php

namespace Notifier\Tests\Unit;

use RuntimeException;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Mediumart\Notifier\NotifierServiceProvider;
use Mediumart\Notifier\Contracts\Channels\Factory;

class NotifierServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function register_notifications_channels_only_process_non_empty_array()
    {
        $manager = $this->app->make(ChannelManager::class);
        $notifier = \Mockery::mock('Mediumart\Notifier\NotifierServiceProvider[getNotificationsChannels]', [$this->app]);

        $notifier->shouldReceive('getNotificationsChannels')->andReturn('not\an\array');
        $notifier->registerNotificationsChannels();
        $this->assertEmpty($manager->getChannels());
    }

    /**
     * @test
     */
    public function register_notifications_channels_only_register_class_that_does_exists()
    {
        $manager = $this->app->make(ChannelManager::class);
        $notifier = \Mockery::mock('Mediumart\Notifier\NotifierServiceProvider[getNotificationsChannels]', [$this->app]);

        // fictive class
        $notifier->shouldReceive('getNotificationsChannels')->andReturn(['Class\That\Does\Not\exists']);
        $notifier->registerNotificationsChannels();
        $this->assertEmpty($manager->getChannels());

        // existing class
        $notifier  = new NotifierServiceProviderTest_ExtendedNotifierServiceProvider($this->app);
        $notifier->registerNotificationsChannels();
        $this->assertSame([TestChannelFactory::class], $manager->getChannels());
    }

    /**
     * @test
     */
    public function get_self_contained_notifications_channels_property()
    {
        $notifier  = new NotifierServiceProviderTest_ExtendedNotifierServiceProvider($this->app);
        $this->assertSame([TestChannelFactory::class], $notifier->getNotificationsChannels());
    }

    /**
     * @test
     */
    public function get_notifications_channels_property_from_another_registered_service_provider()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_AppServiceProvider::class);
        $this->assertSame(['Dummy\TestChannel'],  $notifier->getNotificationsChannels());
    }

    /**
     * @test
     */
    public function get_notifications_channels_return_null_if_notifications_channels_property_dont_exists()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_NullServiceProvider::class);
        $this->assertNull($notifier->getNotificationsChannels());
    }

    /**
     * @test
     */
    public function get_notifications_channels_throws_runtime_exception()
    {
        $notifier = $this->app->getProvider(NotifierServiceProvider::class);
        $notifier->setProviderName(NotifierServiceProviderTest_ExceptionServiceProvider::class);

        $this->expectException(RuntimeException::class);
        $notifier->getNotificationsChannels();
    }

    /**
     * @test
     */
    public function notification_facade_proxy()
    {
        $this->assertInstanceOf(ChannelManager::class, Notification::getFacadeRoot());
    }
}


// /stubs
// 
class TestChannelFactory implements Factory
{
    public static function canHandleNotification($driver)
    {
    }
    public static function createDriver($driver)
    {
    }
}

class NotifierServiceProviderTest_AppServiceProvider extends ServiceProvider
{
    public $notificationsChannels = [
        'Dummy\TestChannel',
    ];
}

class NotifierServiceProviderTest_ExtendedNotifierServiceProvider extends NotifierServiceProvider
{
    protected $notificationsChannels = [
        TestChannelFactory::class
    ];
}

class NotifierServiceProviderTest_ExceptionServiceProvider
{
    protected $notificationsChannels = [] ;
}

class NotifierServiceProviderTest_NullServiceProvider
{
}
