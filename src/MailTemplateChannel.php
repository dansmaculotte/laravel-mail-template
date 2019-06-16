<?php

namespace DansMaCulotte\MailTemplate;

class MailTemplateChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param MailTemplateNotification $notification
     * @return void
     */
    public function send($notifiable, MailTemplateNotification $notification)
    {
        $message = $notification->toMailTemplate($notifiable);

        if ($message instanceof MailTemplate) {
            return $message->send();
        }
    }
}
