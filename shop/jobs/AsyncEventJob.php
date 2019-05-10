<?php

namespace shop\jobs;

class AsyncEventJob extends AJob
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }
}
