<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\Support\ChannelsFactory;

class ChannelsFactoryTest extends TestCase
{
    public function test_channels_factory_create_driver()
    {
        $factory = new ChannelsFactoryTest_MyChannelsFactory();
        $this->assertNull($factory->createDriver('not_supported'));
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $factory->createDriver('test'));
    }
}

class ChannelsFactoryTest_MyChannelsFactory extends ChannelsFactory
{
    public static function canHandleNotification($driver)
    {
       return in_array($driver, ['test']);
    }

    protected function CreateTestDriver($driver)
    {
        return Mockery::mock('Mediumart\Notifier\Contracts\Channels\Dispatcher');
    }
}
