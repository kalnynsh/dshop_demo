<?php

namespace shop\repositories\events;

abstract class AEntityEvent
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
