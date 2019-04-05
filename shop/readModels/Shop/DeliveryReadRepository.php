<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Delivery;

class DeliveryReadRepository
{
    public function getAll(): array
    {
        return Delivery::find()->orderBy('sort ASC')->all();
    }
}
