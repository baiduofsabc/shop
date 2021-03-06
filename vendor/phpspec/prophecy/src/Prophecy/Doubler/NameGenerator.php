<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Doubler;

use ReflectionClass;

/**
 * Name generator.
 * Generates classname for double.
 */
class NameGenerator
{
    private static $counter = 1;

    /**
     * Generates name.
     *
     * @param ReflectionClass   $class
     * @param ReflectionClass[] $interfaces
     *
     * @return string
     */
    public function name(ReflectionClass $class = null, array $interfaces)
    {
        $parts = array();

        if (null !== $class) {
            $parts[] = $class->getName();
        } else {
            foreach ($interfaces as $interface) {
                $parts[] = $interface->getShortName();
            }
        }

        if (!count($parts)) {
            $parts[] = 'stdClass';
        }

        return sprintf('Double\%s\P%d', implode('\\', $parts), self::$counter++);
    }
}
