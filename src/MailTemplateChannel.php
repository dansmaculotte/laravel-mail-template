<?php

namespace DansMaCulotte\MailTemplate;

use DansMaCulotte\MailTemplate\Drivers\Driver;
use Illuminate\Notifications\Notification;

class MailTemplateChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMailTemplate($notifiable);

        if ($message instanceof Driver) {
            $message->send();
        }
    }
}
