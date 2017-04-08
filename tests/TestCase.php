<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'Mediumart\Notifier\NotifierServiceProvider',
            'Tests\Unit\NotifierServiceProviderTest_AppServiceProvider',
        ];
    }
}
