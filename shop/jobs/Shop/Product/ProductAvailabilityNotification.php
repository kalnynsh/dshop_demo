<?php

namespace shop\jobs\Shop\Product;

use shop\jobs\AJob;
use shop\entities\Shop\Product\Product;

class ProductAvailabilityNotification extends AJob
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
