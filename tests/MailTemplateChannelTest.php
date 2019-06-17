<?php


namespace DansMaCulotte\MailTemplate\Tests;

use DansMaCulotte\MailTemplate\Tests\TestCase;
use DansMaCulotte\MailTemplate\MailTemplate;
use DansMaCulotte\MailTemplate\MailTemplateChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable as NotifiableTrait;

class MailTemplateChannelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function should_send_notification_via_specific_channel()
    {
        $user = new Notifiable();
        $user->notify(new WelcomeNotification());

        Notification::assertSentTo(new Notifiable(), WelcomeNotification::class, function ($notification, $usedChannels) {
            return [MailTemplateChannel::class] == $usedChannels;
        });
    }
}

class Notifiable {

    use NotifiableTrait;

    public function getKey()
    {
        return 1;
    }

    public function getFullName()
    {
        return 'John Snow';
    }

    public function getEmail()
    {
        return 'john@stark.westeros';
    }
}

class WelcomeNotification extends \Illuminate\Notifications\Notification
{
    public function via($notifiable)
    {
        return [MailTemplateChannel::class];
    }

    public function toMailTemplate($notifiable)
    {
        return MailTemplate::setSubject('Welcome aboard')
            ->setFrom(config('mail.name'), config('mail.email'))
            ->setRecipient($notifiable->fullName, $notifiable->email)
            ->setLanguage('en')
            ->setTemplate('welcome-aboard')
            ->setVariables([
                'full_name' => $notifiable->fullName,
            ]);
    }
}
