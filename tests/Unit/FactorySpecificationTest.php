<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Notifier\Tests\TestCase;
use Mediumart\Notifier\ChannelManager;
use Mediumart\Notifier\FactorySpecification;

class FactorySpecificationTest extends TestCase
{
    /**
     * @test
     */
    public function _()
    {
        $this->assertTrue((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Factory::class));

        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_NotFactory::class));
        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Static::class));
        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Public::class));
        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Invalid_Params_Count::class));

        if (version_compare(PHP_VERSION, "7.0.0", ">=")) {
            $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Matching_String::class));
        }

        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Retuning_Boolean::class));
    }

    /**
     * @test
     */
    public function channel_manager_register_throws_specification_exception_not_matching_string()
    {
        if (version_compare(PHP_VERSION, "7.0.0", ">=")) {
            $manager = new ChannelManager(null);
            $this->expectException(\Mediumart\Notifier\Exception\SpecificationException::class);
            $manager->register(FactorySpecificationTest_Not_Matching_String::class);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     *
     * @dataProvider testsClasses
     * @expectedException \Mediumart\Notifier\Exception\SpecificationException
     */
    public function channel_manager_register_throws_specification_exception($factoryClassName)
    {
        $manager = new ChannelManager(null);
        $manager->register($factoryClassName);
    }

    /**
     * testsClasses.
     * 
     * @return array
     */
    public function testsClasses() 
    {
        return [
            [FactorySpecificationTest_NotFactory::class],
            [FactorySpecificationTest_Not_Static::class],
            [FactorySpecificationTest_Not_Public::class],
            [FactorySpecificationTest_Invalid_Params_Count::class],
            [FactorySpecificationTest_Not_Retuning_Boolean::class],
        ];
    }
}


// /stubs

class FactorySpecificationTest_NotFactory
{
}

class FactorySpecificationTest_Not_Static
{
    public static function canHandleNotification($driver)
    {
        return true;
    }

    public function createDriver($driver)
    {
        return Mockery::mock();
    }
}

class FactorySpecificationTest_Not_Public
{
    public static function canHandleNotification($driver)
    {
        return true;
    }

    protected static function createDriver($driver)
    {
        return Mockery::mock();
    }
}

class FactorySpecificationTest_Invalid_Params_Count
{
    public static function canHandleNotification($driver, $other)
    {
        return true;
    }

    public static function createDriver($driver)
    {
        return Mockery::mock();
    }
}

class FactorySpecificationTest_Not_Matching_String
{
    public static function canHandleNotification(array $driver)
    {
        return true;
    }

    public static function createDriver($driver)
    {
        return Mockery::mock();
    }
}

class FactorySpecificationTest_Not_Retuning_Boolean
{
    public static function canHandleNotification($driver)
    {
    }

    public static function createDriver($driver)
    {
        return Mockery::mock();
    }
}

class FactorySpecificationTest_Factory
{
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['test']);
    }

    public static function createDriver($driver)
    {
        return Mockery::mock();
    }
}
