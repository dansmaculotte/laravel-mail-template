<?php

namespace DansMaCulotte\MailTemplate\Tests\Sendinblue;


use DansMaCulotte\MailTemplate\Drivers\SendinblueDriver;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use DansMaCulotte\MailTemplate\MailTemplate;
use DansMaCulotte\MailTemplate\Tests\TestCase;
use Mockery;
use SendinBlue\Client\Api\SMTPApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Model\CreateSmtpEmail;

class MailTemplateTest extends TestCase
{
    /** @var \DansMaCulotte\MailTemplate\Drivers\SendinblueDriver */
    protected $driver;

    /** @var Mockery\Mock */
    protected $client;

    /** @var \DansMaCulotte\MailTemplate\MailTemplate */
    protected $mailTemplate;

    public function setUp(): void
    {
        $this->client = Mockery::mock(SMTPApi::class);

        $this->driver = new SendinblueDriver([
            'key' => 'testApiKey',
        ]);
        $this->driver->client = $this->client;
        $this->client->shouldReceive('success')->andReturn(true);

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
    public function should_set_subject()
    {
        $this->mailTemplate->setSubject('test_subject');

        $this->assertTrue($this->driver->message['subject'] === 'test_subject');
    }

    /** @test */
    public function should_set_from()
    {
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email');

        $this->assertTrue($this->driver->message['sender']['name'] === 'test_from_name');
        $this->assertTrue($this->driver->message['sender']['email'] === 'test_from_email');
    }

    /** @test */
    public function should_set_recipient()
    {
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email');

        $varRecipient = null;
        $recipients = $this->driver->message['to'][0];

        $this->assertNotNull($recipients);
        $this->assertTrue($recipients->getName() === 'test_recipient_name');
        $this->assertTrue($recipients->getEmail() === 'test_recipient_email');
    }

    /** @test */
    public function should_set_template()
    {
        $this->mailTemplate->setTemplate(12);

        $this->assertTrue($this->driver->message['templateId'] === 12);
    }

    /** @test */
    public function should_set_variables()
    {
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $this->assertArrayHasKey('test_key', $this->driver->message['params']);
    }

    /** @test */
    public function should_return_array_from_to_array()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email');
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate(12);
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $body = $this->mailTemplate->toArray();

        $this->assertTrue(isset($body['body']));
        $this->assertTrue(isset($body['body']['Messages']));
        $this->assertCount(1, $body['body']['Messages']);
    }

    /** @test */
    public function should_receive_send_successfully()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('martin', 'from@test.fr');
        $this->mailTemplate->setRecipient('gael', 'to@test.fr');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate(1);
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $response = Mockery::mock(CreateSmtpEmail::class);
        $response->shouldReceive('getMessageId')->andReturn('test');

        $this->client->shouldReceive('sendTransacEmail')->andReturn($response);
        $this->mailTemplate->send();
    }

    /** @test */
    public function should_receive_send_and_throw_error()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('martin', 'from@test.fr');
        $this->mailTemplate->setRecipient('gael', 'to@test.fr');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate(1);
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $this->client->shouldReceive('sendTransacEmail')->andThrow(ApiException::class);

        $this->expectExceptionObject(SendError::responseError('sendinblue'));
        $this->mailTemplate->send();
    }
}