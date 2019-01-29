<?php

namespace shop\services\manage\Shop;

use shop\repositories\Shop\ProductRepository;
use shop\forms\manage\Shop\Product\ModificationForm;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Modification;
use shop\repositories\NotFoundException;

class ModificationManageService
{
    /* @property ProductRepository $products */
    private $products;

    public function __construct(
        ProductRepository $products
    ) {
        $this->products = $products;
    }

    public function addModification($productId, ModificationForm $form): void
    {
        $product = $this->products->get($productId);
        $product->addModification(
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );

        $this->products->save($product);
    }

    public function editModification(
        $productId,
        $modificationId,
        ModificationForm $form
    ): void {
        $product = $this->products->get($productId);
        $modification = $this->getModification($product->id, $modificationId);

        $product->editModification(
            $modification->id,
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );

        $this->products->save($product);
    }

    public function removeModification($productId, $modificationId): void
    {
        $product = $this->products->get($productId);
        $modification = $this->getModification($product->id, $modificationId);
        $product->removeModification($modification->id);

        $this->products->save($product);
    }

    public function getModification($productId, $modificationId): Modification
    {
        $product = $this->products->get($productId);

        return $product->getModification($modificationId);
    }

    public function getProductRepository(): ProductRepository
    {
        return $this->products;
    }

    /**
     * Get Product class object by $productId
     *
     * @param int $productId
     * @return Product
     * @throws NotFoundException
     */
    public function getProduct($productId): Product
    {
        return $this->products->get($productId);
    }
}
