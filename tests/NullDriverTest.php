<?php


namespace DansMaCulotte\MailTemplate\Tests;

use Illuminate\Support\Facades\Log;
use Mockery;
use DansMaCulotte\MailTemplate\Drivers\NullDriver;
use PHPUnit\Framework\TestCase;

class NullDriverTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_can_be_call_with_any_method()
    {
        $mailTemplate = new NullDriver();
        $this->assertNotNull($mailTemplate->setTemplate('welcome'));
        $this->assertNotNull($mailTemplate->setRecipient('Recipient', 'recipient@mail.com'));
        $this->assertNotNull($mailTemplate->send());
    }

    /** @test */
    public function it_logs_the_method_call_when_log_is_set()
    {
        $mailTemplate = new NullDriver(true);

        $log = Mockery::mock();
        Log::swap($log);
        $log->shouldReceive('debug')->twice();

        $this->assertNotNull($mailTemplate->setTemplate('welcome'));
        $this->assertNotNull($mailTemplate->setRecipient('Recipient', 'recipient@mail.com'));

        $log->shouldHaveReceived('debug', ['Called Mail Template facade method: setTemplate with: ', ['welcome']]);
        $log->shouldHaveReceived('debug', ['Called Mail Template facade method: setRecipient with: ', ['Recipient', 'recipient@mail.com']]);
    }
}
