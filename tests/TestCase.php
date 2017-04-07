<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'App\providers\AppServiceProvider',
            'Mediumart\Notifier\NotifierServiceProvider',
            'App\providers\NotifierExtendedServiceProvider'
        ];
    }
}
