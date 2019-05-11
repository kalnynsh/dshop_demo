<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Product\Product;
use shop\repositories\NotFoundException;
use shop\dispatchers\IEventDispatcher;
use shop\repositories\events\EntitySaved;
use shop\repositories\events\EntityRemoved;

class ProductRepository
{
    private $dispatcher;

    public function __construct(IEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get($id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Product is not found.');
        }

        return $product;
    }

    public function findOneActive($id): ?Product
    {
        $product = Product::find()
            ->active()
            ->andWhere(['id' => $id])
            ->one();

        if (!$product) {
            return null;
        }

        return $product;
    }

    public function findOneActiveBy($condition): ?Product
    {
        $product = Product::find()
            ->active()
            ->andWhere($condition)
            ->one();

        if (!$product) {
            return null;
        }

        return $product;
    }

    public function existsByBrand($id): bool
    {
        return Product::find()->andWhere(['brand_id' => $id])->exists();
    }

    public function existsByMainCategory($id): bool
    {
        return Product::find()->andWhere(['category_id' => $id])->exists();
    }

    public function save(Product $product): void
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }

        $this->dispatcher->dispatch(new EntitySaved($product));
    }

    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }

        $this->dispatcher->dispatch(new EntityRemoved($product));
    }
}
