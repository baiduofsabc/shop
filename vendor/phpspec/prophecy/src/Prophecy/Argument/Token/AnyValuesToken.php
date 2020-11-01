<?php

/*
 * This file is part of the Prophecy.
 * file that was distributed with this source code.
 */

namespace Prophecy\Argument\Token;

/**
 * Any values token.
 *
 */
class AnyValuesToken implements TokenInterface
{
    /**
     * Always scores 2 for any argument.
     *
     * @param $argument
     *
     * @return int
     */
    public function scoreArgument($argument)
    {
        return 2;
    }

    /**
     * Returns true to stop wildcard from processing other tokens.
     *
     * @return bool
     */
    public function isLast()
    {
        return true;
    }

    /**
     * Returns string representation for token.
     *
     * @return string
     */
    public function __toString()
    {
        return '* [, ...]';
    }
}
