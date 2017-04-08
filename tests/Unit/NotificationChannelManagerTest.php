<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Mediumart\Notifier\ChannelManager;

class NotificationChannelManagerTest extends TestCase
{
    public function test_channel_manager_register_channel_factory()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->assertSame([NotificationChannelManagerTest_Factory::class], $manager->getChannels());
    }

    public function test_channel_manager_register_channel_factory_not_given()
    {
        $manager = new ChannelManager(null);
        $this->expectException(\InvalidArgumentException::class);
        $manager->register(NotificationChannelManagerTest_NotFactory::class);
    }

    public function test_channel_manager_driver_method_returns_channel_dispatcher_instance()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $manager->driver('test'));
    }

    public function test_channel_manager_driver_not_supported()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->expectException(\InvalidArgumentException::class);
        $manager->driver('not_supported');
    }
}

class NotificationChannelManagerTest_NotFactory
{
}

class NotificationChannelManagerTest_Factory implements \Mediumart\Notifier\Contracts\Channels\Factory
{
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['test']);
    }

    public static function createDriver($driver)
    {
        return Mockery::mock('Mediumart\Notifier\Contracts\Channels\Dispatcher');
    }
}
