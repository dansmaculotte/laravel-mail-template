<?php

namespace DansMaCulotte\MailTemplate;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin MailTemplate
 */
class MailTemplateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mail-template';
    }
}
