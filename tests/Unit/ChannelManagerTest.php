<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\ChannelManager;
use Mediumart\Notifier\Exception\SpecificationException;

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
     *
     * @expectedException \Mediumart\Notifier\Exception\SpecificationException
     */
    public function channel_manager_register_channel_factory_not_given()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_NotFactory::class);
        $manager->driver('invalid_factory');
    }

    /**
     * @test
     */
    public function channel_manager_driver_method_returns_channel_driver_instance()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTest_Factory::class);
        $this->assertInstanceOf(NotificationChannelManagerTest_Driver::class, $manager->driver('test'));
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

class NotificationChannelManagerTest_Driver
{
}

class NotificationChannelManagerTest_Factory
{
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['test']);
    }

    public static function createDriver($driver)
    {
        return new NotificationChannelManagerTest_Driver;
    }
}
