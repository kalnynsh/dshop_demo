<?php

namespace shop\listeners\Shop\Product;

use yii\queue\Queue;
use shop\jobs\Shop\Product\ProductAvailabilityNotification;
use shop\entities\Shop\Product\events\ProductAppearedInStock;

class ProductAppearedInStockListener
{
    private $queue;

    public function __construct(
        Queue $queue
    ) {
        $this->queue = $queue;
    }

    public function handle(ProductAppearedInStock $event): void
    {
        if ($event->product->isActive()) {
            $this->queue->push(
                new ProductAvailabilityNotification($event->product)
            );
        }
    }
}
