<?php

namespace shop\services\Shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\cart\cost\Cost;
use shop\repositories\Shop\ProductRepository;

/**
 * CartService class
 *
 * @property Cart $cart
 * @property ProductRepository $products
 */
class CartService
{
    private $cart;
    private $products;

    public function __construct(
        Cart $cart,
        ProductRepository $products
    ) {
        $this->cart = $cart;
        $this->products = $products;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function add($productId, $modificationId, $quantity): void
    {
        $product = $this->products->get($productId);
        $modID =
            $modificationId ?
                $product->getModification($modificationId)->id : null;

        $cartItem = $this->createCartItem($product, $modID, $quantity);

        $this->cart->add($cartItem);
    }

    public function set($id, $quantity): void
    {
        $this->cart->set($id, $quantity);
    }

    public function remove($id): void
    {
        $this->cart->remove($id);
    }

    public function clear(): void
    {
        $this->cart->clear();
    }

    /**
     * Service method return Cart content
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->cart->getItems();
    }

    /**
     * Calculate Cart cost
     * @return Cost
     */
    public function getCost(): Cost
    {
        return $this->cart->getCost();
    }

    /**
     * Calculate total Cart cost
     * @return float
     */
    public function getTotal(): float
    {
        return $this->cart->getCost()->getTotal();
    }


    /**
     * Calculate amount of Cart Items
     * @return int
     */
    public function getAmount(): int
    {
        return $this->cart->getAmount();
    }

    private function createCartItem($product, $modificationId, $quantity)
    {
        return new CartItem($product, $modificationId, $quantity);
    }
}
