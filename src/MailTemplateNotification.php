<?php


namespace DansMaCulotte\MailTemplate;

use Illuminate\Notifications\Notification;

class MailTemplateNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MailTemplateChannel::class];
    }
}
