<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Doubler\ClassPatch;

use Prophecy\Doubler\Generator\Node\ClassNode;

/**
 * ReflectionClass::newInstance patch.
 */
class ReflectionClassNewInstancePatch implements ClassPatchInterface
{
    /**
     * Supports ReflectionClass
     *
     * @return bool
     */
    public function supports(ClassNode $node)
    {
        return 'ReflectionClass' === $node->getParentClass();
    }

    /**
     * Updates newInstance's first argument to make it optional
     *
     * @param ClassNode $node
     */
    public function apply(ClassNode $node)
    {
        foreach ($node->getMethod('newInstance')->getArguments() as $argument) {
            $argument->setDefault(null);
        }
    }

    /**
     * Returns patch priority, which determines when patch will be applied.
     *
     * @return int Priority number (higher = earlier)
     */
    public function getPriority()
    {
        return 50;
    }
}
