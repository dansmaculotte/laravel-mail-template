<?php


namespace DansMaCulotte\MailTemplate\Tests;

use DansMaCulotte\MailTemplate\Drivers\MailgunDriver;
use DansMaCulotte\MailTemplate\Drivers\MailjetDriver;
use DansMaCulotte\MailTemplate\Drivers\MandrillDriver;
use DansMaCulotte\MailTemplate\Drivers\NullDriver;
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
    public function should_instantiate_facade_with_mandrill_driver()
    {
        config()->set('mail-template.driver', 'mandrill');
        config()->set('mail-template.mandrill.secret', 'mandrill');

        $mailTemplate = $this->app[MailTemplate::class];

        $this->assertInstanceOf(MailTemplate::class, $mailTemplate);
        $this->assertInstanceOf(MandrillDriver::class, $mailTemplate->driver);
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
