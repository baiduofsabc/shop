<?php
/*
 * This file is part of the PHP_CodeCoverage package.
 */

/**
 * Interface for code coverage drivers.
 *
 */
interface PHP_CodeCoverage_Driver
{
    /**
     * @var int
     * @see http://xdebug.org/docs/code_coverage
     */
    const LINE_EXECUTED = 1;

    /**
     * @var int
     * @see http://xdebug.org/docs/code_coverage
     */
    const LINE_NOT_EXECUTED = -1;

    /**
     * @var int
     * @see http://xdebug.org/docs/code_coverage
     */
    const LINE_NOT_EXECUTABLE = -2;

    /**
     * Start collection of code coverage information.
     */
    public function start();

    /**
     * Stop collection of code coverage information.
     *
     * @return array
     */
    public function stop();
}
