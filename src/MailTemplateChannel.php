<?php

namespace DansMaCulotte\MailTemplate;

use Illuminate\Notifications\Notification;

class MailTemplateChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMailTemplate($notifiable);
        return $message->sendMessage();
    }
}
