<?php

namespace shop\repositories\events;

class EntitySaved
{
    public $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
