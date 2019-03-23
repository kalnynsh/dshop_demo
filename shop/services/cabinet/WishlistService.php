<?php

namespace shop\services\cabinet;

use shop\repositories\UserRepository;
use shop\repositories\Shop\ProductRepository;
use shop\entities\User\User;
use shop\entities\Shop\Product\Product;

class WishlistService
{
    /** @property UserRepository $users */
    private $users;

    /** @property ProductRepository $products */
    private $products;

    public function __construct(
        UserRepository $users,
        ProductRepository $products
    ) {
        $this->users = $users;
        $this->products = $products;
    }

    public function add($userId, $productId): void
    {
        $user = $this->getUser($userId);
        $product = $this->getProduct($productId);
        $user->addToWishlist($product->id);

        $this->users->save($user);
    }

    public function remove($userId, $productId): void
    {
        $user = $this->getUser($userId);
        $product = $this->getProduct($productId);
        $user->removeFromWishlist($product->id);

        $this->users->save($user);
    }

    public function haveWishlistItems($userId): bool
    {
        $user = $this->getUser($userId);

        return $user->haveWishlistItems();
    }

    private function getUser($userId): User
    {
        return $this->users->get($userId);
    }

    private function getProduct($productId): Product
    {
        return $this->products->get($productId);
    }
}
