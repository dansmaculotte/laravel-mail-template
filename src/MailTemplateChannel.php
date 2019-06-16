<?php

namespace DansMaCulotte\MailTemplate;

class MailTemplateChannel
{
    public function send($notifiable, MailTemplateNotification $notification)
    {
        $message = $notification->toMailTemplate($notifiable);

        if ($message instanceof MailTemplate) {
            return $message->send();
        }
    }
}
