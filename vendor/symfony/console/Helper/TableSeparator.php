<?php

/*
 * This file is part of the Symfony package.
 */

namespace Symfony\Component\Console\Helper;

/**
 * Marks a row as being a separator.
 *
 */
class TableSeparator extends TableCell
{
    public function __construct(array $options = array())
    {
        parent::__construct('', $options);
    }
}
