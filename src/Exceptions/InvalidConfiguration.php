<?php


namespace DansMaCulotte\MailTemplate\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidConfiguration
 * @package DansMaCulotte\MailTemplate\Exceptions
 */
class InvalidConfiguration extends Exception
{
    /**
     * @param string $name
     * @param string $key
     * @param int $code
     * @param Throwable|null $previous
     * @return InvalidConfiguration
     */
    public static function invalidCredential($name, $key, $code = 0, Throwable $previous = null)
    {
        return new static("Credential `{$key}` for `{$name}` is invalid.", $code, $previous);
    }

    /**
     * @param $name
     * @return InvalidConfiguration
     */
    public static function driverNotFound($name)
    {
        return new static("Driver {$name} not found.");
    }
}
