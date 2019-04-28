<?php

namespace shop\dispatchers;

class DeferredEventDispatcher implements IEventDispatcher
{
    private $delayed = false;
    private $queue = [];
    private $next;

    public function __construct(IEventDispatcher $next)
    {
        $this->next = $next;
    }

    public function dispatch($event): void
    {
        if ($this->delayed) {
            $this->queue[] = $event;
        }

        if (!$this->delayed) {
            $this->next->dispatch($event);
        }
    }

    public function defer(): void
    {
        $this->delayed = true;
    }

    public function clean(): void
    {
        $this->queue = [];
        $this->delayed = false;
    }

    public function release(): void
    {
        foreach ($this->queue as $idx => $event) {
            $this->next->dispatch($event);
            unset($this->queue[$idx]);
        }

        $this->delayed = false;
    }
}
