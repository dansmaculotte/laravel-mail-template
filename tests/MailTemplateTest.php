<?php


namespace DansMaCulotte\MailTemplate\Tests;

use DansMaCulotte\MailTemplate\Drivers\Driver;
use DansMaCulotte\MailTemplate\MailTemplate;
use Mockery;
use PHPUnit\Framework\TestCase;

class MailTemplateTest extends TestCase
{
    /** @var \DansMaCulotte\MailTemplate\Drivers\Driver */
    protected $driver;

    /** @var MailTemplate */
    protected $mailTemplate;

    public function setUp(): void
    {
        parent::setUp();
        $this->driver = Mockery::mock(Driver::class);

        $this->mailTemplate = new MailTemplate($this->driver);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
    }

    /** @test */
    public function should_prepare_successfully()
    {
        $this->driver->shouldReceive(
            'setSubject',
            'setFrom',
            'setRecipient',
            'setLanguage',
            'setTemplate',
            'setVariables'
        )->andReturnSelf();

        $this->mailTemplate->prepare(
            'test_subject',
            [
                'name' => 'from_name',
                'email' => 'from_email',
            ],
            [
                'name' => 'recipient_name',
                'email' => 'recipient_email',
            ],
            'test_template',
            'test_language',
            [
                'test_var' => 'test_value',
            ]
        );
    }
}
