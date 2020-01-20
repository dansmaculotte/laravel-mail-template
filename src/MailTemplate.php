<?php

namespace DansMaCulotte\MailTemplate;

use DansMaCulotte\MailTemplate\Drivers\Driver;

/**
 * @method self setSubject(string $string)
 * @method self setFrom(string $name, string $email)
 * @method self setRecipient(string $name, string $email)
 * @method self setLanguage(string $language)
 * @method self setTemplate(string $template)
 * @method self addAttachment(string $file, string $name)
 * @method self trackClicks(bool $enable)
 * @method self trackOpens(bool $enable)
 * @method self setVariables(array $variables)
 * @method array toArray()
 */
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
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        return $this->driver->$name(...$arguments);
    }
}
