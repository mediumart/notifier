<?php

namespace Mediumart\Notifier;

use ReflectionClass;

class FactorySpecification
{
    /**
     * Error.
     * 
     * @var array
     */
    public static $error;

    /**
     * Check if channel matches factory specifications.
     *  
     * @param  string  $channel
     * @return boolean
     */
    public function isSatisfiedBy($channel)
    {
        return $this->methodsExists(
            new ReflectionClass($channel), ['canHandleNotification', 'createDriver']
        );
    }

    /**
     * Check methods exists on the channel.
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function methodsExists($channel, $methods)
    {
        foreach ((array) $methods as $method) {
            if (! $channel->hasMethod($method)) {
                static::$error = ['methodsNotExists', $method];

                return false;
            }
        }

        return $this->declaredAsStatic($channel, $methods);
    }

    /**
     * Check methods are declared as static.
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function declaredAsStatic($channel, $methods)
    {
        foreach ((array) $methods as $method) {
            if (! $channel->getMethod($method)->isStatic()) {
                static::$error = ['methodsNotStatic', $method];

                return false;
            }
        }

        return $this->declaredAsPublic($channel, $methods);
    }

    /**
     * Check methods are declared as public.
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function declaredAsPublic($channel, $methods)
    {
        foreach ((array) $methods as $method) {
            if (! $channel->getMethod($method)->isPublic()) {
                static::$error = ['methodsNotPublic', $method];

                return false;
            }
        }

        return $this->expectsExactlyOneParameter($channel, $methods);
    }

    /**
     * Check methods expects exactly one parameter.
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function expectsExactlyOneParameter($channel, $methods)
    {
        foreach ((array) $methods as $method) {
            if (($count = $channel->getMethod($method)->getNumberOfParameters()) !== 1) {
                static::$error = ['invalidParamsCount', $method, $count];

                return false;
            }
        }

        return $this->parameterMatchesString($channel, $methods);
    }

    /**
     * Check methods matches string type values.
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function parameterMatchesString($channel, $methods)
    {
        foreach ((array) $methods as $method) {
            $parameter = $channel->getMethod($method)->getParameters()[0];

            if (! method_exists($parameter, 'getType')) {
                continue;
            }

            if (! is_null($type = $parameter->getType()) && (string) $type != 'string') {
                static::$error = ['invalidParamsType', $method, $parameter->name];

                return false;
            }
        }

        return $this->methodReturnsBoolean($channel, $methods);
    }

    /**
     * Check methods returns boolean
     * 
     * @param  ReflectionClass $channel
     * @param  array|string $methods
     * @return boolean
     */
    protected function methodReturnsBoolean($channel, $methods)
    {
        if (! is_bool($channel->getMethod('canHandleNotification')->invoke(null, 'driver'))) {
            static::$error = ['shouldReturnBoolean', 'canHandleNotification'];

            return false;
        }

        return true;
    }
} 
