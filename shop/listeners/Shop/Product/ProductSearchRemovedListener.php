<?php

namespace shop\listeners\Shop\Product;

use shop\services\search\ProductIndexer;
use shop\repositories\events\EntityRemoved;
use shop\entities\Shop\Product\Product;
use yii\caching\Cache;
use yii\caching\TagDependency;

class ProductSearchRemovedListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntityRemoved $event): void
    {
        $entity = $event->entity;

        if ($entity instaceof Product) {
            $this->indexer->remove($entity);
            TagDependency::invalidate($this->cache, ['products']);
        }
    }
}
