<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Exception\Doubler;

class MethodNotFoundException extends DoubleException
{
    /**
     * @var string|object
     */
    private $classname;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $message
     * @param string|object $classname
     * @param string $methodName
     * @param null|Argument\ArgumentsWildcard|array $arguments
     */
    public function __construct($message, $classname, $methodName, $arguments = null)
    {
        parent::__construct($message);

        $this->classname  = $classname;
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    public function getClassname()
    {
        return $this->classname;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
