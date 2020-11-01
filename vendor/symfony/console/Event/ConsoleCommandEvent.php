<?php

/*
 * This file is part of the Symfony package.
 */

namespace Symfony\Component\Console\Event;

/**
 */
class ConsoleCommandEvent extends ConsoleEvent
{
    /**
     * The return code for skipped commands, this will also be passed into the terminate event.
     */
    const RETURN_CODE_DISABLED = 113;

    /**
     * Indicates if the command should be run or skipped.
     */
    private $commandShouldRun = true;

    /**
     * Disables the command, so it won't be run.
     *
     * @return bool
     */
    public function disableCommand()
    {
        return $this->commandShouldRun = false;
    }

    /**
     * Enables the command.
     *
     * @return bool
     */
    public function enableCommand()
    {
        return $this->commandShouldRun = true;
    }

    /**
     * Returns true if the command is runnable, false otherwise.
     *
     * @return bool
     */
    public function commandShouldRun()
    {
        return $this->commandShouldRun;
    }
}
