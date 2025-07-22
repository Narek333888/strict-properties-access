<?php

namespace ArmDevStack\StrictPropertiesAccess\Classes\Base;

use ArmDevStack\StrictPropertiesAccess\Contracts\Base\StrictPropertyAccessInterface;
use ArmDevStack\StrictPropertiesAccess\Traits\StrictPropertyAccess;

/**
 * Provides an abstract base class implementing
 * StrictPropertyAccessInterface and applying the
 * StrictPropertyAccess trait.
 * Intended to be extended by other models
 * that need strict property access control.
 */
abstract class AbstractStrictModel implements StrictPropertyAccessInterface
{
    use StrictPropertyAccess;
}