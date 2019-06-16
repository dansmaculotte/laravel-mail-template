<?php


namespace DansMaCulotte\MailTemplate\Tests\Mandrill;

use DansMaCulotte\MailTemplate\Drivers\MailjetDriver;
use DansMaCulotte\MailTemplate\Drivers\MandrillDriver;
use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use DansMaCulotte\MailTemplate\MailTemplate;
use Mailjet\Client;
use Mailjet\Request;
use Mailjet\Response;
use Mandrill;
use Mandrill_Error;
use Mandrill_Messages;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MailTemplateTest extends TestCase
{
    /** @var \DansMaCulotte\MailTemplate\Drivers\MandrillDriver */
    protected $driver;

    /** @var Mockery\Mock */
    protected $client;

    /** @var \DansMaCulotte\MailTemplate\MailTemplate */
    protected $mailTemplate;

    public function setUp()
    {
        $this->client = Mockery::mock(Mandrill::class)->makePartial();

        $this->driver = new MandrillDriver([
            'key' => 'testApiKey',
            'secret' => 'testApiSecret',
        ]);
        $this->driver->client = $this->client;
        $this->client->shouldReceive('success')->andReturn(true);

        $this->mailTemplate = new MailTemplate($this->driver);
    }

    public function tearDown()
    {
        parent::tearDown();
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
    }

    /** @test */
    public function should_throw_error_with_secret()
    {
        $this->expectExceptionObject(InvalidConfiguration::invalidCredential('mandrill', 'secret'));

        $driver = new MandrillDriver([
            'key' => 'test',
        ]);

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

        $this->assertTrue($this->driver->message['from_name'] === 'test_from_name');
        $this->assertTrue($this->driver->message['from_email'] === 'test_from_email');
    }

    /** @test */
    public function should_set_recipient()
    {
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email');

        $varRecipient = null;
        $recipients = $this->driver->message['to'];

        foreach ($recipients as $recipient) {
            if ($recipient['name'] === 'test_recipient_name') {
                $varRecipient = $recipient;
            }
        }

        $this->assertNotNull($varRecipient);
        $this->assertTrue($varRecipient['name'] === 'test_recipient_name');
        $this->assertTrue($varRecipient['email'] === 'test_recipient_email');
        $this->assertTrue($varRecipient['type'] === 'to');
    }

    /** @test */
    public function should_set_language()
    {
        $this->mailTemplate->setLanguage('test');

        $languageVar = null;
        $vars = $this->driver->message['global_merge_vars'];
        foreach ($vars as $var) {
            if ($var['name'] === 'MC_LANGUAGE') {
                $languageVar = $var;
            }
        }

        $this->assertNotNull($languageVar);
        $this->assertTrue($languageVar['content'] === 'test');
    }

    /** @test */
    public function should_set_template()
    {
        $this->mailTemplate->setTemplate('test_template');

        $this->assertTrue($this->driver->body['template'] === 'test_template');
    }

    /** @test */
    public function should_set_variables()
    {
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $testVar = null;
        $vars = $this->driver->message['global_merge_vars'];
        foreach ($vars as $var) {
            if ($var['name'] === strtoupper('test_key')) {
                $testVar = $var;
            }
        }

        $this->assertNotNull($testVar);
        $this->assertTrue($testVar['content'] === 'test_value');
    }

    /** @test */
    public function should_receive_send_successfully()
    {
        $this->mailTemplate->setTemplate('test_template');

        $this->client->messages = Mockery::mock(Mandrill_Messages::class)
            ->shouldReceive('sendTemplate')
            ->andReturn([])
            ->getMock();

        $this->mailTemplate->send();
    }

    /** @test */
    public function should_receive_send_and_throw_error()
    {
        $this->mailTemplate->setTemplate('test_template');

        $this->client->messages = Mockery::mock(Mandrill_Messages::class)
            ->shouldReceive('sendTemplate')
            ->andThrow(Mandrill_Error::class)
            ->getMock();

        $this->expectExceptionObject(SendError::responseError('mandrill'));

        $this->mailTemplate->send();
    }

    /** @test */
    public function should_return_array_from_to_array()
    {
        $this->mailTemplate->setSubject('test_subject');
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email');
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email');
        $this->mailTemplate->setLanguage('test');
        $this->mailTemplate->setTemplate('test_template');
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $body = $this->mailTemplate->toArray();

        $this->assertTrue(isset($body['body']));
        $this->assertTrue(isset($body['body']['message']));
    }
}
