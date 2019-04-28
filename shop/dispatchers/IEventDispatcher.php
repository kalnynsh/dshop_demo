<?php

namespace shop\dispatchers;

interface IEventDispatcher
{
    public function dispatch($event): void;
    public function dispatchAll(array $events): void;
}
