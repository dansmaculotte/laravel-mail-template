<?php


namespace DansMaCulotte\MailTemplate\Tests;

use DansMaCulotte\MailTemplate\Drivers\MailchimpDriver;
use DansMaCulotte\MailTemplate\Drivers\MailgunDriver;
use DansMaCulotte\MailTemplate\Drivers\MailjetDriver;
use DansMaCulotte\MailTemplate\Drivers\NullDriver;
use DansMaCulotte\MailTemplate\Drivers\SendgridDriver;
use DansMaCulotte\MailTemplate\Drivers\SendinblueDriver;
use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\MailTemplate;

class MailTemplateFacadeTest extends TestCase
{
    /** @test */
    public function should_instantiate_facade_with_null_driver()
    {
        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(NullDriver::class, $mailTemplate);
    }

    /** @test */
    public function should_instantiate_facade_with_mailjet_driver()
    {
        config()->set('mail-template.driver', 'mailjet');
        config()->set('mail-template.mailjet.key', 'mailjet');
        config()->set('mail-template.mailjet.secret', 'mailjet');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(MailjetDriver::class, $mailTemplate->driver);
    }

    /** @test */
    public function should_instantiate_facade_with_sendinblue_driver()
    {
        config()->set('mail-template.driver', 'sendinblue');
        config()->set('mail-template.sendinblue.key', 'sendinblue');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(SendinblueDriver::class, $mailTemplate->driver);
    }

    /** @test */
    public function should_instantiate_facade_with_mailchimp_driver()
    {
        config()->set('mail-template.driver', 'mailchimp');
        config()->set('mail-template.mailchimp.secret', 'mailchimp');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(MailchimpDriver::class, $mailTemplate->driver);
    }

    /** @test */
    public function should_instantiate_facade_with_sendgrid_driver()
    {
        config()->set('mail-template.driver', 'sendgrid');
        config()->set('mail-template.sendgrid.key', 'sendgrid');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(SendgridDriver::class, $mailTemplate->driver);
    }

    /** @test */
    public function should_instantiate_facade_with_mailgun_driver()
    {
        config()->set('mail-template.driver', 'mailgun');
        config()->set('mail-template.mailgun.key', 'mailgun-key');
        config()->set('mail-template.mailgun.domain', 'example.com');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(MailgunDriver::class, $mailTemplate->driver);
    }

    /** @test */
    public function should_throw_error_on_register()
    {
        config()->set('mail-template.driver', 'invalid');

        $this->expectExceptionObject(InvalidConfiguration::driverNotFound('invalid'));
        $this->app[MailTemplate::class];
    }
}
