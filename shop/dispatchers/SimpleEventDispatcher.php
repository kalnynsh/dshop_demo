<?php

namespace shop\dispatchers;

use yii\di\Container;
use shop\dispatchers\IEventDispatcher;

class SimpleEventDispatcher implements IEventDispatcher
{
    private $container;
    private $listeners;

    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    public function dispatch($event): void
    {
        $evantName = \get_class($event);

        if (\array_key_exists($evantName, $this->listeners)) {
            foreach ($this->listeners[$evantName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    private function resolveListener($listenerClass): callable
    {
        return [$this->container->get($listenerClass), 'handle'];
    }
}
