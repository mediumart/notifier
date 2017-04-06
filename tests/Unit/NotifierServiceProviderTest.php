<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $notificationsChannels = [
        \Mediumart\Notifier\Channels\DefaultChannel::class,
    ];
}

namespace Tests\Unit;

use Tests\TestCase;
use Mediumart\Notifier\ChannelManager;

class NotifierServiceProviderTest extends TestCase
{
    public function testRegisterNotificationsChannels()
    {
        $manager =$this->app->make(Channelmanager::class);
        $this->assertSame(['Mediumart\Notifier\Channels\DefaultChannel'], $manager->getChannels());
    }
}
