<?php

namespace DansMaCulotte\MailTemplate;

use DansMaCulotte\MailTemplate\Drivers\MailjetDriver;
use DansMaCulotte\MailTemplate\Drivers\MandrillDriver;
use DansMaCulotte\MailTemplate\Drivers\NullDriver;
use Illuminate\Support\ServiceProvider;

class MailTemplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mailtemplate.php', 'mailtemplate');

        $this->publishes([
            __DIR__.'/../config/mailtemplate.php' => config_path('mailtemplate.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(MailTemplate::class, function () {

            $driver = config('mailtemplate.driver', null);
            if (is_null($driver) || $driver === 'log') {
                return new NullDriver($driver === 'log');
            }

            switch ($driver) {
                case 'mailjet':
                    $driver = new MailjetDriver(config('mailtemplate.mailjet'));
                    break;
                case 'mandrill':
                    $driver = new MandrillDriver(config('mailtemplate.mandrill'));
                    break;
                default:
                    break;
            }

            return new MailTemplate($driver);
        });

        $this->app->make(MailTemplateChannel::class);

        $this->app->alias(MailTemplate::class, 'mailtemplate');
    }
}
