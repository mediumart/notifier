<?php

namespace Mediumart\Notifier\Contracts\Channels;

use Illuminate\Notifications\Notification;

Interface Dispatcher
{
    /**
     * Send the given notification.
     *
     * @param  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification);
}
