<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Events\Dispatcher;
use Mediumart\Notifier\Channels\MailChannel;
use Mediumart\Notifier\Channels\DefaultChannel;

class NotificationDefaultChannelTest extends TestCase
{
    public function test_default_channel_create_mail_driver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('mail'));

        $container = new Container;
        $container->instance(Mailer::class, $mailer = Mockery::mock('Illuminate\Contracts\Mail\Mailer'));
        $container->instance(MailChannel::class, $mailChannel = Mockery::spy($container->make(MailChannel::class)));
        Container::setInstance($container);

        $driver = DefaultChannel::createDriver('mail');
        $mailChannel->shouldHaveReceived('setMarkdownResolver')->with(Mockery::type('Closure'));

        $this->assertInstanceOf('Mediumart\Notifier\Channels\MailChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function test_default_channel_create_broadcast_driver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('broadcast'));

        $container = new Container;
        $container->instance(Dispatcher::class, $events = Mockery::mock('Illuminate\Contracts\Events\Dispatcher'));
        Container::setInstance($container);
        $driver = DefaultChannel::createDriver('broadcast');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\BroadcastChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function test_default_channel_create_database_driver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('database'));
        $driver = DefaultChannel::createDriver('database');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\DatabaseChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function test_default_channel_create_slack_driver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('slack'));
        $driver = DefaultChannel::createDriver('slack');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\SlackWebhookChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function test_default_channel_create_nexmo_driver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('nexmo'));

        $container = new Container;
        $container->instance('config', ['services.nexmo.key' => null, 'services.nexmo.secret' => null, 'services.nexmo.sms_from' => null]);
        Container::setInstance($container);

        $driver = DefaultChannel::createDriver('nexmo');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\NexmoSmsChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function test_default_channel_driver_not_suppported()
    {
        $this->assertFalse(DefaultChannel::canHandleNotification('notSupported'));
        $this->assertNull(DefaultChannel::createDriver('notSupported'));
    }
}
