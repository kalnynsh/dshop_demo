<?php

namespace shop\listeners\Shop\Product;

use shop\services\search\ProductIndexer;
use shop\repositories\events\EntitySaved;
use shop\entities\Shop\Product\Product;

class ProductSearchSavedListener
{
    private $indexer;

    public function __construct(ProductIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function handle(EntitySaved $event): void
    {
        $entity = $event->entity;

        if ($entity instaceof Product) {
            if ($entity->isActive()) {
                $this->indexer->index($entity);
            }

            if (!$entity->isActive()) {
                $this->indexer->remove($entity);
            }
        }
    }
}
