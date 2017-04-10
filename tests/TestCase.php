<?php

namespace Notifier\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'Mediumart\Notifier\NotifierServiceProvider',
            'Notifier\Tests\Unit\NotifierServiceProviderTest_AppServiceProvider',
        ];
    }
}
