<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Exception\Doubler;

class ClassNotFoundException extends DoubleException
{
    private $classname;

    /**
     * @param string $message
     * @param string $classname
     */
    public function __construct($message, $classname)
    {
        parent::__construct($message);

        $this->classname = $classname;
    }

    public function getClassname()
    {
        return $this->classname;
    }
}
