<?php

namespace Mediumart\Notifier\Support;

use Mediumart\Notifier\Contracts\Channels\Factory;
use Mediumart\Notifier\Contracts\Channels\Dispatcher;

/**
 * Class NotificationChannel.
 */
abstract class NotificationChannel implements Factory, Dispatcher
{
}
