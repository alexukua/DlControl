<?php

namespace Zs\Model;

class EprintsAdapter implements DLAdapter
{

    /**
     * @var Eprints
     */
    private $eprints;

    public function __construct(Eprints $eprints)
    {
        $this->eprints = $eprints;
    }

    public function LoadMetadata()
    {
        // TODO: Implement LoadMetadata() method.

        $this->eprints->LoadMetadata();

    }
}