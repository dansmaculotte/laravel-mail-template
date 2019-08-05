<?php

namespace DansMaCulotte\MailTemplate\Tests\Sendgrid;

use DansMaCulotte\MailTemplate\Drivers\SendgridDriver;
use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use DansMaCulotte\MailTemplate\MailTemplate;
use DansMaCulotte\MailTemplate\Tests\TestCase;
use Mockery;
use Mockery\Mock;
use SendGrid;
use SendGrid\Response;

class MailTemplateTest extends TestCase
{
    /** @var SendgridDriver */
    protected $driver;

    /** @var Mock */
    protected $client;

    /** @var MailTemplate */
    protected $mailTemplate;

    public function setUp(): void
    {
        $this->client = Mockery::mock(SendGrid::class);

        $this->driver = new SendgridDriver([
            'key' => 'test-key',
            'domain' => 'test.mailgun.org'
        ]);

        $this->driver->client = $this->client;

        $this->mailTemplate = new MailTemplate($this->driver);
    }
    public function tearDown(): void
    {
        parent::tearDown();
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
    }
    /** @test */
    public function should_throw_error_with_key()
    {
        $this->expectExceptionObject(InvalidConfiguration::invalidCredential('sendgrid', 'key'));
        $driver = new SendgridDriver([
            'domain' => 'test',
        ]);
    }

    /** @test */
    public function should_set_from()
    {
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email@email.fr');

        $this->assertTrue($this->driver->message->getFrom()->getName() === "test_from_name");
        $this->assertTrue($this->driver->message->getFrom()->getEmail() === "test_from_email@email.fr");
    }

    /** @test */
    public function should_set_recipient()
    {
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email@email.fr');
        $recipient = $this->driver->message->getPersonalizations()[0]->getTos()[0];

        $this->assertTrue($recipient->getEmail() === "test_recipient_email@email.fr");
        $this->assertTrue($recipient->getName() === "test_recipient_name");
    }

    /** @test */
    public function should_set_template()
    {
        $this->mailTemplate->setTemplate('test');
        $this->assertTrue($this->driver->message->getTemplateId()->getTemplateId() === 'test');
    }

    /** @test */
    public function should_set_variables()
    {
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);
        $this->assertArrayHasKey('test_key', $this->driver->message->getSubstitutions());
        $this->assertEquals('test_value', $this->driver->message->getSubstitutions()['test_key']);
    }

    /** @test */
    public function should_return_array_from_to_array()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email@email.fr');
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email@email.fr');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate('test');
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);
        $body = $this->mailTemplate->toArray();
        $this->assertTrue(isset($body['body']));
        $this->assertTrue(isset($body['body']['message']));
    }

    /** @test */
    public function should_receive_send_successfully()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('martin', 'martin@dansmaculotte.fr');
        $this->mailTemplate->setRecipient('martin potel', 'mrtn.potel@gmail.com');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate('d-1f953e28e43d433c81cc3f1c05d10822');
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('headers')->andReturn([]);

        $this->client->shouldReceive('send')->andReturn($response);

        $response = $this->mailTemplate->send();
        $this->assertEquals([], $response);

    }

    /** @test */
    public function should_receive_send_and_throw_error()
    {
        $this->client->shouldReceive('send')->andThrow(\Exception::class);

        $this->expectExceptionObject(SendError::responseError('sendgrid'));
        $this->mailTemplate->send();
    }
}