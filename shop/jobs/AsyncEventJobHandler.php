<?php

namespace shop\jobs;

use shop\dispatchers\IEventDispatcher;

class AsyncEventJobHandler
{
    private $dispatcher;

    public function __construct(IEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(AsyncEventJob $job): void
    {
        $this->dispatcher->dispatch($job->event);
    }
}
