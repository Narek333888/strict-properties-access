<?php

namespace ArmDevStack\StrictPropertiesAccess\Traits;

use ArmDevStack\StrictPropertiesAccess\Contracts\Loggers\LoggerInterface;
use ArmDevStack\StrictPropertiesAccess\Contracts\Observers\PropertyAccessObserverInterface;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionProperty;

/**
 * A trait that enforces strict property access rules.
 * Prevents dynamic properties and provides
 * detailed error handling via logger or observer.
 */
trait StrictPropertyAccess
{
    /**
     * Reflects the current class.
     *
     * @var ReflectionClass|null
     */
    private ?ReflectionClass $reflectionClass;

    /**
     * Filter for which properties to reflect (defaults to public).
     *
     * @var int
     */
    protected int $propertyFilter = ReflectionProperty::IS_PUBLIC;

    /**
     * Optional logger for error logging.
     *
     * @var LoggerInterface|null
     */
    protected ?LoggerInterface $logger = null;

    /**
     * Optional observer for property access.
     *
     * @var PropertyAccessObserverInterface|null
     */
    protected ?PropertyAccessObserverInterface $propertyAccessObserver = null;

    /**
     * Mode: 'echo', 'log', or 'both'.
     *
     * @var string
     */
    protected string $errorOutputMode = 'both';

    /**
     * List of all defined properties.
     *
     * @var array
     */
    private array $properties = [];

    /**
     * Whether strict mode is enabled.
     *
     * @var bool
     */
    protected bool $strictMode = true;

    /**
     * Whether to throw exceptions or echo.
     *
     * @var bool
     */
    protected bool $throwExceptions = false;

    /**
     * Tracks invalid property accesses.
     *
     * @var array
     */
    protected array $invalidAccesses = [];

    /**
     * Initializes reflection and fills properties.
     */
    public function __construct()
    {
        $this->reflectionClass = new ReflectionClass(self::class);
        $this->fillProperties();
    }

    /**
     * Handles access to undefined properties.
     * Delegates to observer and logger if defined.
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

            if ($this->propertyAccessObserver)
            {
                $this->propertyAccessObserver->onMissingProperty($propName);
            }

            if (method_exists($this, 'handleMissingProperty'))
            {
                $this->handleMissingProperty($propName);

                return;
            }

            $this->handleError($message);
        }
    }

    /**
     * Prevents dynamic property creation, unless strict mode is disabled.
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

        if ($this->propertyAccessObserver)
        {
            $this->propertyAccessObserver->onDynamicPropertyCreationAttempt($name, $value);
        }

        $message = 'Deprecated: Creation of dynamic property is deprecated' . $newLine;

        $this->handleError($message);
    }

    /**
     * Checks if a property exists in class.
     *
     * @param string $propName
     * @return bool
     */
    protected function propIsExist(string $propName): bool
    {
        return in_array($propName, $this->properties);
    }

    /**
     * Logs message using logger if available.
     *
     * @param string $message
     * @return void
     */
    protected function logAccessError(string $message): void
    {
        if ($this->logger)
        {
            $this->logger->log($message);
        }
    }

    /**
     * Echoes or throws exception, and logs if needed.
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
     * Populates properties list from reflection.
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
     * Turns on strict property mode.
     *
     * @return void
     */
    public function enableStrictMode(): void
    {
        $this->strictMode = true;
    }

    /**
     * Turns off strict property mode.
     *
     * @return void
     */
    public function disableStrictMode(): void
    {
        $this->strictMode = false;
    }

    /**
     * Enables exception throwing on invalid access.
     *
     * @return void
     */
    public function enableExceptions(): void
    {
        $this->throwExceptions = true;
    }

    /**
     * Disables exception throwing.
     *
     * @return void
     */
    public function disableExceptions(): void
    {
        $this->throwExceptions = false;
    }

    /**
     * Retrieves properties using reflection and filter.
     *
     * @return array
     */
    protected function getProperties(): array
    {
        return $this->reflectionClass->getProperties($this->propertyFilter);
    }

    /**
     * Returns list of invalid accesses.
     *
     * @return array
     */
    public function getInvalidAccesses(): array
    {
        return $this->invalidAccesses;
    }

    /**
     * Changes reflection filter (e.g., private, protected).
     *
     * @param int $filter
     * @return void
     */
    public function setPropertyFilter(int $filter): void
    {
        $this->propertyFilter = $filter;
    }

    /**
     * Assigns a logger.
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Assigns an observer.
     *
     * @param PropertyAccessObserverInterface $observer
     * @return void
     */
    public function setPropertyAccessObserver(PropertyAccessObserverInterface $observer): void
    {
        $this->propertyAccessObserver = $observer;
    }

    /**
     * Sets output mode for error handling; throws if invalid.
     *
     * @param string $mode
     * @return void
     */
    public function setErrorOutputMode(string  $mode): void
    {
        $allowed = ['echo', 'log', 'both'];

        if(!in_array($mode, $allowed))
        {
            throw new InvalidArgumentException("Invalid error output mode");
        }
    }
}
