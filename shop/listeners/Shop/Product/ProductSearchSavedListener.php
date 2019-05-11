<?php

namespace shop\listeners\Shop\Product;

use shop\services\search\ProductIndexer;
use shop\repositories\events\EntitySaved;
use shop\entities\Shop\Product\Product;
use yii\caching\TagDependency;

class ProductSearchSavedListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntitySaved $event): void
    {
        $entity = $event->entity;

        if ($entity instaceof Product) {
            if ($entity->isActive()) {
                $this->indexer->index($entity);
                $this->tagDependencyValidate();
            }

            if (!$entity->isActive()) {
                $this->indexer->remove($entity);
                $this->tagDependencyValidate();
            }
        }
    }

    private function tagDependencyValidate(): void
    {
        TagDependency::invalidate($this->cache, ['products']);
    }
}
