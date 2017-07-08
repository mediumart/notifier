<?php 

namespace Mediumart\Notifier\Exception;

use Exception;
use Mediumart\Notifier\FactorySpecification;

class SpecificationException extends Exception
{
    /**
     * Exception.
     *
     * @return SpecificationException
     */
    public function exception($channel)
    {
        return static::{array_shift(FactorySpecification::$error)}($channel, ...FactorySpecification::$error);
    }

    /**
     * methodsNotExists exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @return SpecificationException
     */
    protected static function methodsNotExists($channel, $method)
    {
        return new static(
            sprintf("Missing public static method [%s] on factory [%s]", $method, $channel)
        );
    }

    /**
     * methodsNotStatic exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @return SpecificationException
     */
    protected static function methodsNotStatic($channel, $method)
    {
        return new static(
            sprintf("Method [%s] on factory [%s] should declared as 'public' and 'static'.", $method, $channel)
        );
    }

    /**
     * methodsNotPublic exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @return SpecificationException
     */
    protected static function methodsNotPublic($channel, $method)
    {
        return new static(
            sprintf("Method [%s] on factory [%s] should declared as 'public' and 'static'.", $method, $channel)
        );
    }

    /**
     * invalidParamsCount exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @param  int $count
     * @return SpecificationException
     */
    protected static function invalidParamsCount($channel, $method, $count)
    {   
        return new static(
            sprintf("Method [%s] on factory [%s] should expect exactly 1 parameter, %s declared.", $method, $channel, $count)
        );
    }

    /**
     * invalidParamsType exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @param  string $name
     * @return SpecificationException
     */
    protected static function invalidParamsType($channel, $method, $name)
    {
        return new static(
            sprintf("Parameter [%s] for method [%s] on factory [%s] should be of type 'string'.", $name, $method, $channel)
        );
    }

    /**
     * shouldReturnBoolean exception.
     * 
     * @param  string $channel
     * @param  string $method
     * @param  string $name
     * @return SpecificationException
     */
    protected static function shouldReturnBoolean($channel, $method)
    {
        return new static(
            sprintf("Method [%s] on factory [%s] should return a Boolean", $method, $channel)
        );
    }
}
