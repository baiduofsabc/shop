<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Comparator;

use SebastianBergmann\Comparator\Factory as BaseFactory;

/**
 * Prophecy comparator factory.
 */
final class Factory extends BaseFactory
{
    /**
     * @var Factory
     */
    private static $instance;

    public function __construct()
    {
        parent::__construct();

        $this->register(new ClosureComparator());
        $this->register(new ProphecyComparator());
    }

    /**
     * @return Factory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Factory;
        }

        return self::$instance;
    }
}
