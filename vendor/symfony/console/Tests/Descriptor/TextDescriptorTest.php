<?php

/*
 * This file is part of the Symfony package.
 */

namespace Symfony\Component\Console\Tests\Descriptor;

use Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Tests\Fixtures\DescriptorApplicationMbString;
use Symfony\Component\Console\Tests\Fixtures\DescriptorCommandMbString;

class TextDescriptorTest extends AbstractDescriptorTest
{
    public function getDescribeCommandTestData()
    {
        return $this->getDescriptionTestData(array_merge(
            ObjectsProvider::getCommands(),
            array('command_mbstring' => new DescriptorCommandMbString())
        ));
    }

    public function getDescribeApplicationTestData()
    {
        return $this->getDescriptionTestData(array_merge(
            ObjectsProvider::getApplications(),
            array('application_mbstring' => new DescriptorApplicationMbString())
        ));
    }

    protected function getDescriptor()
    {
        return new TextDescriptor();
    }

    protected function getFormat()
    {
        return 'txt';
    }
}
