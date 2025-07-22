<?php

namespace ArmDevStack\StrictPropertiesAccess\Observers;

use ArmDevStack\StrictPropertiesAccess\Contracts\Observers\PropertyAccessObserverInterface;

/**
 * Observer that outputs information to console or web about invalid property operations.
 */
class DebugObserver implements PropertyAccessObserverInterface
{
    /**
     * Echoes a warning message for missing properties.
     *
     * @param string $property
     * @return void
     */
    public function onMissingProperty(string $property): void
    {
        echo "⚠️ [Observer] Attempt to access non-existent property: $property" . PHP_EOL;
    }

    /**
     * Echoes a warning for dynamic property creation attempts.
     *
     * @param string $property
     * @param $value
     * @return void
     */
    public function onDynamicPropertyCreationAttempt(string $property, $value): void
    {
        echo "⚠️ [Observer] Attempt to create dynamic property: $property with value " . var_export($value, true) . PHP_EOL;
    }
}