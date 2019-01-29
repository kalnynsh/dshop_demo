<?php

namespace shop\cart\storage;

use shop\cart\CartItem;

interface StorageInterface
{
    /**
     * load CartItem method
     *
     * @return CartItem[]
     */
    public function load(): array;

    /**
     * save CartItem[] method
     *
     * @param CartItem[] $items
     */
    public function save(array $items): void;
}
