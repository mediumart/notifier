<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Mediumart\Notifier\ChannelManager;

class NotificationChannelManagerTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testChannelManagerRegisterChannelFactory()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTestNotificationChannelFactory::class);

        $this->assertSame([NotificationChannelManagerTestNotificationChannelFactory::class], $manager->getChannels());
    }

    public function testChannelManagerRegisterChannelFactoryNotGiven()
    {
        $manager = new ChannelManager(null);

        $this->expectException(\InvalidArgumentException::class);
        $manager->register(NotificationChannelManagerTestNotFactory::class);
    }

    public function testChannelManagerDriverMethodReturnsChannelDispatcherInstance()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTestNotificationChannelFactory::class);

        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $manager->driver('test'));
    }

    public function testChannelManagerDriverNotSupported()
    {
        $manager = new ChannelManager(null);
        $manager->register(NotificationChannelManagerTestNotificationChannelFactory::class);

        $this->expectException(\InvalidArgumentException::class);
        $manager->driver('notSupported');
    }
}

class NotificationChannelManagerTestNotFactory
{
}

class NotificationChannelManagerTestNotificationChannelFactory implements \Mediumart\Notifier\Contracts\Channels\Factory
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
