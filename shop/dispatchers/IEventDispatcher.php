<?php

namespace shop\dispatchers;

interface IEventDispatcher
{
    public function dispatch($event): void;
}
