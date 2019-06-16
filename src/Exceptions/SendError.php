<?php


namespace DansMaCulotte\MailTemplate\Exceptions;

use Exception;
use Throwable;

/**
 * Class SendError
 * @package DansMaCulotte\MailTemplate\Exceptions
 */
class SendError extends Exception
{
    /**
     * @param string $name
     * @param array $body
     * @param int $code
     * @param Throwable|null $previous
     * @return SendError
     */
    public static function responseError(string $name, $code = 0, Throwable $previous = null)
    {
        return new static("Send method for `{$name}` returned an error.", $code, $previous);
    }
}
