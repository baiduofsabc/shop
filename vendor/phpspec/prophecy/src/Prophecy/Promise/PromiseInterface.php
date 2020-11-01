<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Promise;

use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophecy\MethodProphecy;

/**
 * Promise interface.
 * Promises are logical blocks, tied to `will...` keyword.
 *
 */
interface PromiseInterface
{
    /**
     * Evaluates promise.
     *
     * @param array          $args
     * @param ObjectProphecy $object
     * @param MethodProphecy $method
     *
     * @return mixed
     */
    public function execute(array $args, ObjectProphecy $object, MethodProphecy $method);
}
