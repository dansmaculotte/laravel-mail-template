<?php

namespace DansMaCulotte\MailTemplate;

use Illuminate\Support\Facades\Facade;

/**
 * @see MailTemplate
 * @method MailTemplate make()
 * @method MailTemplate setFrom(string $name, string $email)
 * @method MailTemplate setTemplate($template)
 * @method MailTemplate setSubject(string $subject)
 * @method MailTemplate setRecipient(string $name, string $email)
 * @method MailTemplate setVariables(array $variables)
 * @method MailTemplate setLanguage(string $language)
 * @method array send()
 * @method array toArray()
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
