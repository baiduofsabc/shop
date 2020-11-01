<?php

/*
 * This file is part of the Symfony package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Fixtures;

use Symfony\Component\Console\Application;

class DescriptorApplicationMbString extends Application
{
    public function __construct()
    {
        parent::__construct('MbString åpplicätion');

        $this->add(new DescriptorCommandMbString());
    }
}
