<?php


namespace DansMaCulotte\MailTemplate\Tests\Mailjet;

use DansMaCulotte\MailTemplate\Drivers\MailjetDriver;
use DansMaCulotte\MailTemplate\Exceptions\InvalidConfiguration;
use DansMaCulotte\MailTemplate\Exceptions\SendError;
use DansMaCulotte\MailTemplate\MailTemplate;
use Mailjet\Client;
use Mailjet\Request;
use Mailjet\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MailTemplateTest extends TestCase
{
    /** @var \DansMaCulotte\MailTemplate\Drivers\MailjetDriver */
    protected $driver;

    /** @var Mockery\Mock */
    protected $client;

    /** @var \DansMaCulotte\MailTemplate\MailTemplate */
    protected $mailTemplate;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);

        $this->driver = new MailjetDriver([
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
    public function should_throw_error_with_key()
    {
        $this->expectExceptionObject(InvalidConfiguration::invalidCredential('mailjet', 'key'));

        $driver = new MailjetDriver([
            'secret' => 'test',
        ]);
    }

    /** @test */
    public function should_throw_error_with_secret()
    {
        $this->expectExceptionObject(InvalidConfiguration::invalidCredential('mailjet', 'secret'));

        $driver = new MailjetDriver([
            'key' => 'test',
        ]);

    }

    /** @test */
    public function should_set_subject()
    {
        $this->mailTemplate->setSubject('test_subject');

        $this->assertTrue($this->driver->message['Subject'] === 'test_subject');
    }

    /** @test */
    public function should_set_from()
    {
        $this->mailTemplate->setFrom('test_from_name', 'test_from_email');

        $this->assertTrue($this->driver->message['From']['Name'] === 'test_from_name');
        $this->assertTrue($this->driver->message['From']['Email'] === 'test_from_email');
    }

    /** @test */
    public function should_set_recipient()
    {
        $this->mailTemplate->setRecipient('test_recipient_name', 'test_recipient_email');

        $varRecipient = null;
        $recipients = $this->driver->message['To'];

        foreach ($recipients as $recipient) {
            if ($recipient['Name'] === 'test_recipient_name') {
                $varRecipient = $recipient;
            }
        }

        $this->assertNotNull($varRecipient);
        $this->assertTrue($varRecipient['Name'] === 'test_recipient_name');
        $this->assertTrue($varRecipient['Email'] === 'test_recipient_email');
    }

    /** @test */
    public function should_set_language()
    {
        $this->mailTemplate->setLanguage('test');

        $this->assertTrue($this->driver->message['Variables']['language'] === 'test');
    }

    /** @test */
    public function should_set_template()
    {
        $this->mailTemplate->setTemplate('1654984165');

        $this->assertTrue($this->driver->message['TemplateID'] === intval('1654984165'));
        $this->assertTrue($this->driver->message['TemplateLanguage'] === true);
    }

    /** @test */
    public function should_set_variables()
    {
        $this->mailTemplate->setVariables([
            'test_key' => 'test_value'
        ]);

        $this->assertCount(1, $this->driver->message['Variables']);
        $this->assertTrue($this->driver->message['Variables']['test_key'] === 'test_value');
    }

    /** @test */
    public function should_receive_send_successfully()
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn('{}');

        $this->client->shouldReceive('post')->andReturn(new Response($request, $response));

        $this->mailTemplate->send();
    }

    /** @test */
    public function should_receive_send_and_throw_error()
    {
        $request = Mockery::mock(Request::class);
        $response = Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturn('{}');

        $this->client
            ->shouldReceive('post')
            ->andReturn(new Response($request, $response));

        $this->expectExceptionObject(SendError::responseError('mailjet'));

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
        $this->assertTrue(isset($body['body']['Messages']));
        $this->assertCount(1, $body['body']['Messages']);
    }
}
