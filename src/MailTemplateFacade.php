<?php

namespace DansMaCulotte\MailTemplate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DansMaCulotte\MailTemplate\MailTemplate
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
