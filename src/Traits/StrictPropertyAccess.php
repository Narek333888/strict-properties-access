<?php

namespace ArmDevStack\StrictPropertiesAccess\Traits;

use LogicException;
use ReflectionClass;

/**
 * Trait StrictPropertyAccess
 *
 * Prevents dynamic property creation and provides strict property existence checks.
 */
trait StrictPropertyAccess
{
    /**
     * Reflection class instance of the using class.
     *
     * @var ReflectionClass|null
     */
    private ?ReflectionClass $reflectionClass;

    /**
     * List of existing property names of the using class.
     *
     * @var array
     */
    private array $properties = [];

    /**
     * Toggle strict mode
     *
     * @var bool
     */
    protected bool $strictMode = true;

    /**
     * Throw exception instead of echo
     *
     * @var bool
     */
    protected bool $throwExceptions = false;

    /**
     * Track invalid accesses
     *
     * @var array
     */
    protected array $invalidAccesses = [];

    /**
     * Constructor initializes the ReflectionClass and fills existing properties.
     */
    public function __construct()
    {
        $this->reflectionClass = new ReflectionClass(self::class);
        $this->fillProperties();
    }

    /**
     * Magic getter to prevent access to undefined properties.
     *
     * @param string $propName
     * @return void
     */
    public function __get(string $propName): void
    {
        $newLine = (php_sapi_name() == 'cli') ? PHP_EOL : '<br>';

        if (!$this->strictMode)
            return;

        if (!$this->propIsExist($propName))
        {
            $message =  "Prop '$propName' does not exist!!!" . $newLine;

            $this->invalidAccesses[] = $propName;

            if (method_exists($this, 'handleMissingProperty'))
            {
                $this->handleMissingProperty($propName);

                return;
            }

            $this->handleError($message);
        }
    }

    /**
     * Magic setter to prevent creation of dynamic properties.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $newLine = (php_sapi_name() == 'cli') ? PHP_EOL : '<br>';

        if (!$this->strictMode)
        {
            $this->$name = $value;

            return;
        }

        $message = 'Deprecated: Creation of dynamic property is deprecated' . $newLine;

        $this->handleError($message);
    }

    /**
     * Check if the given property exists in the class.
     *
     * @param string $propName
     * @return bool
     */
    protected function propIsExist(string $propName): bool
    {
        return in_array($propName, $this->properties);
    }

    /**
     * Optional logging
     *
     * @param string $message
     * @return void
     */
    protected function logAccessError(string $message): void
    {
        error_log('[StrictPropertyAccess] ' . trim($message));
    }

    /**
     * Handle error output
     *
     * @param string $message
     * @return void
     */
    protected function handleError(string $message): void
    {
        if ($this->throwExceptions)
        {
            throw new LogicException(trim($message));
        }
        else
        {
            echo $message;

            $this->logAccessError($message);
        }
    }

    /**
     * Populate the properties array with existing property names.
     *
     * @return void
     */
    protected function fillProperties(): void
    {
        foreach ($this->getProperties() as $property)
        {
            $this->properties[] = $property->name;
        }
    }

    /**
     * Enable strict mode
     *
     * @return void
     */
    public function enableStrictMode(): void
    {
        $this->strictMode = true;
    }

    /**
     * Disable strict mode
     *
     * @return void
     */
    public function disableStrictMode(): void
    {
        $this->strictMode = false;
    }

    /**
     * Enable exceptions
     *
     * @return void
     */
    public function enableExceptions(): void
    {
        $this->throwExceptions = true;
    }

    /**
     * Disable exceptions
     *
     * @return void
     */
    public function disableExceptions(): void
    {
        $this->throwExceptions = false;
    }

    /**
     * Retrieve all properties of the class using reflection.
     *
     * @return array
     */
    protected function getProperties(): array
    {
        return $this->reflectionClass->getProperties();
    }

    /**
     * Get invalid accesses array
     *
     * @return array
     */
    public function getInvalidAccesses(): array
    {
        return $this->invalidAccesses;
    }
}
