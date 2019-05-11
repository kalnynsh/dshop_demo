<?php

namespace shop\listeners\Shop\Product;

use shop\services\search\ProductIndexer;
use shop\repositories\events\EntityRemoved;
use shop\entities\Shop\Product\Product;

class ProductSearchRemovedListener
{
    private $indexer;

    public function __construct(ProductIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function handle(EntityRemoved $event): void
    {
        $entity = $event->entity;

        if ($entity instaceof Product) {
            $this->indexer->remove($entity);
        }
    }
}
