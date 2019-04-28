<?php

namespace shop\jobs;

abstract class AJob implements \yii\queue\Job
{
    public function execute($queue): void
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }

    private function resolveHandler(): callable
    {
        return [\Yii::createObject(static::class . Handler), 'handle'];
    }
}
