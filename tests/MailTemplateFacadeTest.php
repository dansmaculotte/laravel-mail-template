<?php


namespace DansMaCulotte\MailTemplate\Tests;


use DansMaCulotte\MailTemplate\MailTemplateFacade;

class MailTemplateFacadeTest extends TestCase
{
    /** @test */
    public function should_instanciate_facade()
    {
        $mailTemplate = $this->app[MailTemplateFacade::class];

        $this->assertNotNull($mailTemplate);
    }
}
