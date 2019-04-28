<?php

namespace shop\entities\aggregators;

interface AggregateRoot
{
    /**
     * @return array
     */
    public function realeaseEvents(): array;
}
