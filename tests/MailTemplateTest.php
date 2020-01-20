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
            'addAttachment',
            'trackClicks',
            'trackOpens',
            'setVariables'
        )->andReturnSelf();

        $this->mailTemplate
            ->setSubject('Welcome aboard')
            ->setFrom('From name', 'from@email.com')
            ->setRecipient('Recipient Name', 'recipient@email.com')
            ->setLanguage('en')
            ->setTemplate('welcome-aboard')
            ->addAttachment('pdf/invoice.pdf', 'invoice-42.pdf')
            ->trackClicks(true)
            ->trackOpens(true)
            ->setVariables([
                'first_name' => 'Recipient',
            ]);
    }
}
