<?php

namespace shop\listeners\Shop\Category;

use yii\caching\TagDependency;
use yii\caching\Cache;
use shop\repositories\events\AEntityEvent;
use shop\entities\Shop\Category;

class CategoryPersistenceListener
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function handle(AEntityEvent $event): void
    {
        if ($event->entity instanceof Category) {
            TagDependency::invalidate($this->cache, ['categories']);
        }
    }
}
