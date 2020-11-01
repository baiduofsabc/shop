<?php

/*
 * This file is part of the Prophecy.
 */

namespace Prophecy\Prophecy;

/**
 * Controllable doubles interface.
 *
 */
interface ProphecySubjectInterface
{
    /**
     * Sets subject prophecy.
     *
     * @param ProphecyInterface $prophecy
     */
    public function setProphecy(ProphecyInterface $prophecy);

    /**
     * Returns subject prophecy.
     *
     * @return ProphecyInterface
     */
    public function getProphecy();
}
