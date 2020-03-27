<?php

namespace DansMaCulotte\MailTemplate;

use DansMaCulotte\MailTemplate\Drivers\Driver;

class MailTemplate
{
    /** @var Driver|null */
    public $driver = null;

    /**
     * MailTemplate constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param $subject
     * @param $from
     * @param $recipient
     * @param $template
     * @param $language
     * @param $variables
     * @return Driver
     */
    public function prepare($subject, $from, $recipient, $template, $language, $variables)
    {
        return $this->driver
            ->make()
            ->setSubject($subject)
            ->setFrom($from['name'], $from['email'])
            ->setRecipient($recipient['name'], $recipient['email'])
            ->setLanguage($language)
            ->setTemplate($template)
            ->setVariables($variables);
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        return $this->driver->$name(...$arguments);
    }
}
