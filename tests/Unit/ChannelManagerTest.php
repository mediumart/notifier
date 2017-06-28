<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\ChannelManager;

class ChannelManagerTest extends TestCase
{
    /**
     * @test
     */
    public function channel_manager_register_channel_factory()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->assertSame([NotificationChannelManagerTest_Factory::class], $manager->getChannels());
    }

    /**
     * @test
     */
    public function channel_manager_register_channel_factory_not_given()
    {
        $manager = new ChannelManager(null);
        $this->expectException(\InvalidArgumentException::class);
        $manager->register(NotificationChannelManagerTest_NotFactory::class);
    }

    /**
     * @test
     */
    public function channel_manager_driver_method_returns_channel_dispatcher_instance()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $manager->driver('test'));
    }

    /**
     * @test
     */
    public function channel_manager_driver_not_supported()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->expectException(\InvalidArgumentException::class);
        $manager->driver('not_supported');
    }
}


// /stubs

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
