<?php

namespace ArmDevStack\StrictPropertiesAccess\Contracts\Base;

/**
 * Defines the contract for enabling/disabling strict mode and exceptions,
 * and retrieving invalid accesses.
 */
interface StrictPropertyAccessInterface
{
    /**
     * Enables strict property access mode.
     *
     * @return void
     */
    public function enableStrictMode(): void;

    /**
     * Disables strict property access mode.
     *
     * @return void
     */
    public function disableStrictMode(): void;

    /**
     * Enables throwing exceptions on errors.
     *
     * @return void
     */
    public function enableExceptions(): void;

    /**
     * Disables throwing exceptions, defaulting to echo/log.
     *
     * @return void
     */
    public function disableExceptions(): void;

    /**
     * Returns a list of invalid property access attempts.
     *
     * @return array
     */
    public function getInvalidAccesses(): array;
}