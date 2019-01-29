<?php

namespace shop\cart;

use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Modification;

/**
 * CartItem class
 *
 * @property Product $product
 * @property int|string $modificationId
 * @property int $quantity
 */
class CartItem
{
    private $product;
    private $modificationId;
    private $quantity;

    public function __construct(
        Product $product,
        $modificationId,
        $quantity
    ) {
        if (!$product->canBeCheckout($modificationId, $quantity)) {
            throw new \DomainException('Now we do not have such quantity.');
        }

        $this->product = $product;
        $this->modificationId = $modificationId;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return md5(serialize([$this->product->id, $this->modificationId]));
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductId(): int
    {
        return $this->product->id;
    }

    public function getModification(): ?Modification
    {
        if ($this->modificationId) {
            return $this->product->getModification($this->modificationId);
        }

        return null;
    }

    public function getModificationId(): ?int
    {
        return $this->modificationId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        if ($this->modificationId) {
            return $this->product->getModificationPrice($this->modificationId);
        }

        return $this->product->price_new;
    }

    public function getCost(): float
    {
        return $this->getPrice() * $this->getQuantity();
    }

    public function getWeight(): int
    {
        return $this->product->weight * $this->getQuantity();
    }

    public function plus($addedQuantity): self
    {
        return new static(
            $this->product,
            $this->modificationId,
            $this->quantity + $addedQuantity
        );
    }

    public function changeQuantity($quantity): self
    {
        return new static(
            $this->product,
            $this->modificationId,
            $quantity
        );
    }
}
