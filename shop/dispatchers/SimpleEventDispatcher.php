<?php

namespace shop\dispatchers;

use shop\dispatchers\IEventDispatcher;

class SimpleEventDispatcher implements IEventDispatcher
{
    private $listeners;

    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }

    public function dispatch($event): void
    {
        $evantName = \get_class($event);

        if (\array_key_exists($evantName, $this->listeners)) {
            foreach ($this->listeners[$evantName] as $listener) {
                $listener($event);
            }
        }
    }
}
