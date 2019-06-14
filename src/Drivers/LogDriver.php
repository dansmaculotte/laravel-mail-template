<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use Illuminate\Support\Facades\Log;

class LogDriver
{
    /**
     * @var bool
     */
    private $logCalls;

    public function __construct($logCalls = false)
    {
        $this->logCalls = $logCalls;
    }

    public function __call($name, $arguments)
    {
        if ($this->logCalls) {
            Log::debug('Called Mail Template facade method: ' . $name . ' with: ', $arguments);
        }

        return $this;
    }

}
