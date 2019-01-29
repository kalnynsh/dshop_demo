<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Discount;
use shop\repositories\NotFoundException;

class DiscountRepository
{
    public function find($id): ?Discount
    {
        if (!$discount = Discount::findOne($id)->active()) {
            return null;
        }

        return $discount;
    }

    public function findAll(): array
    {
        $discount = Discount::find()->active()->orderBy('sort')->all();

        if (!$discount) {
            return [];
        }

        return $discount;
    }

    public function get($id): Discount
    {
        if (!$discount = Discount::findOne($id)) {
            throw new NotFoundException('Brand is not found.');
        }

        return $discount;
    }

    public function save(Discount $discount): void
    {
        if (!$discount->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Discount $discount): void
    {
        if (!$discount->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

}
