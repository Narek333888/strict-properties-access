<?php

namespace ArmDevStack\StrictPropertiesAccess\Traits;

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

        if (!$this->propIsExist($propName))
        {
            echo "Prop '$propName' does not exist!!!" . $newLine;
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

        echo 'Deprecated: Creation of dynamic property is deprecated' . $newLine;
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
     * Retrieve all properties of the class using reflection.
     *
     * @return array
     */
    protected function getProperties(): array
    {
        return $this->reflectionClass->getProperties();
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
}
