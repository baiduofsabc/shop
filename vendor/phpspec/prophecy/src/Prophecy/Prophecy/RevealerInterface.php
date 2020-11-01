<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Prophecy;

/**
 * Prophecies revealer interface.
 *
 */
interface RevealerInterface
{
    /**
     * Unwraps value(s).
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function reveal($value);
}
