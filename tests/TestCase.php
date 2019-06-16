<?php


namespace DansMaCulotte\MailTemplate\Tests;


use DansMaCulotte\MailTemplate\MailTemplateServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MailTemplateServiceProvider::class,
        ];
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        config()->set('mail-template.driver', 'log');
    }
}
