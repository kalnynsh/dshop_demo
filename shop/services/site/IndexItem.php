<?php

namespace shop\services\site;

class IndexItem
{
    public $location;
    public $lastModified;

    public function __construct(
        $location,
        $lastModified = null
    ) {
        $this->location =  $location;
        $this->lastModified = $lastModified;
    }
}
