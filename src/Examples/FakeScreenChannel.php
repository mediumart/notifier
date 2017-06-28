<?php

namespace Mediumart\Notifier\Examples;

use Illuminate\Notifications\Notification;
use Mediumart\Notifier\Contracts\Channels\Factory;

/**
 * Use this class to quickly **taste** the package.
 * register the channel as explained in the documentation in App\Providers\AppServiceProvider:
 *
 *      public $notificationsChannels = [
 *          \Mediumart\Notifier\Examples\FakeScreenChannel::class
 *      ]
 *
 * create a new notification:
 *     `$ php artisan make:notification SomethingHappenedNotification`
 *
 * open the newly created notification class, and add the hook 'screen' to the `via`method:
 *     public function via($notifiable)
 *     {
 *         return ['screen'];
 *     }
 *
 * define a `toScreen` method that returns whatever message you want:
 *     public function toScreen($notifiable)
 *     {
 *         return 'Thank you for using our application!';
 *     }
 *
 * now this message will just be die an dump to the screen when you notify any notifiable
 * instance of the `SomethingHappenedNotification`.
 */
class FakeScreenChannel implements Factory
{
    /**
     * Send the given notification.
     *
     * @param  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        dd($notification->toScreen($notifiable));
    }

    /**
     * Check for the factory capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['screen']);
    }

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return mixed|\Mediumart\Notifier\Contracts\Channels\Dispatcher
     */
    public static function createDriver($driver)
    {
        return static::canHandleNotification($driver) ? new static : null;
    }
}
