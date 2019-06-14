<?php

namespace DansMaCulotte\MailTemplate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DansMaCulotte\MailTemplate\MailTemplateClass
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
        return 'mailtemplate';
    }
}
