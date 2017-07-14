<?php

namespace Notifier\Tests\Unit;;

use Notifier\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use Illuminate\Support\Facades\Artisan;
use Mediumart\Notifier\Console\NotifierChannelCommand;

class NotifierChannelCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_generate_notifier_channel()
    {
        // mock filesystem
        $root = vfsStream::setup(basename(base_path()), null, [
            'app' => []
        ]);

        $this->app->instance('path', $root->getChild('app')->url());

        $command = new NotifierChannelCommand($this->app->make('files'));
        $command->setLaravel($this->app);

        Artisan::call('notifier:channel', ['name' => 'FakeTestChannel', '--aliases' => 'foo,bar']);

        $this->assertTrue(file_exists($this->app->make('path').'/Notifications/Channels/FakeTestChannel.php'));;
    }
}
