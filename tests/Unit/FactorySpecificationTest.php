<?php

namespace Notifier\Tests\Unit;

use Mockery;
use Notifier\Tests\TestCase;
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
        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Matching_String::class));
        $this->assertFalse((new FactorySpecification)->isSatisfiedBy(FactorySpecificationTest_Not_Retuning_Boolean::class));
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
