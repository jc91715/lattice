<?php

namespace Jc91715\Lattice;

interface OutInterface
{
    public function outAll($positions,$sections,$spreadSections,$charRowCount);

    public function outRow($row,$spreadSections);
}