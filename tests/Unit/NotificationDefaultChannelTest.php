<?php

namespace Nexmo;

use Nexmo\Client\Credentials\Basic as NexmoCredentials;

class Client
{
    public function __construct(NexmoCredentials $credentials)
    {
    }
}

namespace Nexmo\Client\Credentials;

class Basic
{
    public function __construct($key, $secret)
    {
    }
}

namespace GuzzleHttp;

class Client
{
}

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Container\Container;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Events\Dispatcher;
use Mediumart\Notifier\Channels\MailChannel;
use Mediumart\Notifier\Channels\DefaultChannel;

class NotificationDefaultChannelTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testDefaultChannelCreateMailDriver()
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

    public function testDefaultChannelCreateBroadcastDriver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('broadcast'));

        $container = new Container;
        $container->instance(Dispatcher::class, $events = Mockery::mock('Illuminate\Contracts\Events\Dispatcher'));
        Container::setInstance($container);
        $driver = DefaultChannel::createDriver('broadcast');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\BroadcastChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function testDefaultChannelCreateDatabaseDriver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('database'));

        $driver = DefaultChannel::createDriver('database');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\DatabaseChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function testDefaultChannelCreateSlackDriver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('slack'));

        $container = new Container;
        $container->instance(HttpClient::class, $http = Mockery::mock('GuzzleHttp\Client'));
        Container::setInstance($container);

        $driver = DefaultChannel::createDriver('slack');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\SlackWebhookChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function testDefaultChannelCreateNexmoDriver()
    {
        $this->assertTrue(DefaultChannel::canHandleNotification('nexmo'));

        $container = new Container;
        $container->instance('config', ['services.nexmo.key' => null, 'services.nexmo.secret' => null, 'services.nexmo.sms_from' => null]);
        Container::setInstance($container);

        $driver = DefaultChannel::createDriver('nexmo');

        $this->assertInstanceOf('Mediumart\Notifier\Channels\NexmoSmsChannel', $driver);
        $this->assertInstanceOf('Mediumart\Notifier\Contracts\Channels\Dispatcher', $driver);
    }

    public function testDefaultChannelDriverNotSuppported()
    {
        $this->assertFalse(DefaultChannel::canHandleNotification('notSupported'));
        $this->assertNull(DefaultChannel::createDriver('notSupported'));
    }
}
