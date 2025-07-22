<?php

namespace ArmDevStack\StrictPropertiesAccess\Contracts\Loggers;

/**
 * Defines a simple logging contract for error messages.
 */
interface LoggerInterface
{
    /**
     * Logs the provided message.
     *
     * @param string $message
     * @return void
     */
    public function log(string $message): void;
}