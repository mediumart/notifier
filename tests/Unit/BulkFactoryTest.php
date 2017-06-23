<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Exception;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\Support\BulkFactory;

class BulkFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function channels_factory_create_driver()
    {
        $factory = new Extended_BulkFactory();
        $this->assertNull($factory->createDriver('not_supported'));
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $factory->createDriver('test'));
    }

    /**
     * @test
     */
    public function channels_factory_create_driver_missing_method()
    {
        $factory = new Extended_BulkFactory_Missing_Method();
        $this->expectException(Exception::class);
        $factory->createDriver('test');
    }
}


// /stubs
// 
class Extended_BulkFactory extends BulkFactory
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

class Extended_BulkFactory_Missing_Method extends BulkFactory
{
    public static function canHandleNotification($driver)
    {
       return in_array($driver, ['test']);
    }
}
