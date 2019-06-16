<?php


namespace DansMaCulotte\MailTemplate\Exceptions;

use Exception;

class SendError extends Exception
{
    /**
     * @param $name
     * @return SendError
     */
    public static function responseError($name)
    {
        return new static("Send method for `{$name}` return an error.");
    }
}
