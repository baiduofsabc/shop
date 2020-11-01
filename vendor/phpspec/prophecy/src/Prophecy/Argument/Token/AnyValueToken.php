<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Argument\Token;

/**
 * Any single value token.
 */
class AnyValueToken implements TokenInterface
{
    /**
     * Always scores 3 for any argument.
     *
     * @param $argument
     *
     * @return int
     */
    public function scoreArgument($argument)
    {
        return 3;
    }

    /**
     * Returns false.
     *
     * @return bool
     */
    public function isLast()
    {
        return false;
    }

    /**
     * Returns string representation for token.
     *
     * @return string
     */
    public function __toString()
    {
        return '*';
    }
}
