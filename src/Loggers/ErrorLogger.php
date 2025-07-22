<?php

namespace ArmDevStack\StrictPropertiesAccess\Loggers;

use ArmDevStack\StrictPropertiesAccess\Contracts\Loggers\LoggerInterface;

/**
 * A simple implementation of LoggerInterface that logs messages to PHP error log.
 */
class ErrorLogger implements LoggerInterface
{
    /**
     * Logs the message with [StrictPropertyAccess] prefix.
     *
     * @param string $message
     * @return void
     */
    public function log(string $message): void
    {
        error_log('[StrictPropertyAccess] ' . trim($message));
    }
}