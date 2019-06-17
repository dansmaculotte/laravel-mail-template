<?php

namespace DansMaCulotte\MailTemplate\Drivers;

use Illuminate\Support\Facades\Log;

/**
 * Class NullDriver
 * @package DansMaCulotte\MailTemplate\Drivers
 */
class NullDriver
{
    /**
     * @var bool
     */
    private $logCalls;

    /**
     * NullDriver constructor.
     * @param bool $logCalls
     */
    public function __construct(bool $logCalls = false)
    {
        $this->logCalls = $logCalls;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if ($this->logCalls) {
            Log::debug('Called Mail Template facade method: ' . $name . ' with: ', $arguments);
        }

        return $this;
    }
}
