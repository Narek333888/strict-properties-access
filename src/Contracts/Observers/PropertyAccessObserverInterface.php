<?php

namespace ArmDevStack\StrictPropertiesAccess\Contracts\Observers;

/**
 * Contract for observers monitoring property access behavior.
 */
interface PropertyAccessObserverInterface
{
    /**
     * Called when a non-existent property is accessed.
     *
     * @param string $property
     * @return mixed
     */
    public function onMissingProperty(string $property);

    /**
     * Called when an attempt is made to create a dynamic property.
     *
     * @param string $property
     * @param $value
     * @return mixed
     */
    public function onDynamicPropertyCreationAttempt(string $property, $value);
}