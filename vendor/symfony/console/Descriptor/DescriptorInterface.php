<?php

/*
 * This file is part of the Symfony package.
 */

namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Output\OutputInterface;

/**
 */
interface DescriptorInterface
{
    /**
     * Describes an object if supported.
     *
     * @param OutputInterface $output
     * @param object          $object
     * @param array           $options
     */
    public function describe(OutputInterface $output, $object, array $options = array());
}
