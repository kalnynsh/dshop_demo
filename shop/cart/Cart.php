<?php

namespace shop\cart;

use shop\cart\storage\StorageInterface;
use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\cost\Cost;
use shop\cart\CartItem;

/**
 * Cart class
 *
 * @property StorageInterface $storage
 * @property CalculatorInterface $calculator
 * @property CartItem[] $items
 */
class Cart
{
    private $storage;
    private $calculator;
    private $items;

    public function __construct(
        StorageInterface $storage,
        CalculatorInterface $calculator
    ) {
        $this->storage = $storage;
        $this->calculator = $calculator;
    }

    /**
     * getItems function
     *
     * @return CartItem[]
     */
    public function getItems(): array
    {
        $this->loadItems();

        return $this->items;
    }

    public function getAmount(): int
    {
        return count($this->getItems());
    }

    public function add(CartItem $addedItem): void
    {
        $this->loadItems();

        foreach ($this->items as $idx => $current) {
            if ($current->getId() == $addedItem->getId()) {
                $this->items[$idx] = $current->plus($addedItem->getQuantity());

                $this->saveItems();
                return;
            }
        }

        $this->items[] = $addedItem;
        $this->saveItems();
    }

    public function set($id, $quantity): void
    {
        $this->loadItems();

        foreach ($this->items as $idx => $current) {
            if ($current->getId() == $id) {
                $this->items[$idx] = $current->changeQuantity($quantity);

                $this->saveItems();
                return;
            }
        }

        throw new \DomainException('This Item not found.');
    }

    public function remove($id): void
    {
        $this->loadItems();

        foreach ($this->items as $idx => $current) {
            if ($current->getId() == $id) {
                unset($this->items[$idx]);

                $this->saveItems();
                return;
            }
        }

        throw new \DomainException('This Item not found.');
    }

    public function clear(): void
    {
        $this->items = [];
        $this->saveItems();
    }

    public function getCost(): Cost
    {
        return $this->calculator->getCost($this->getItems());
    }

    public function getWeight(): int
    {
        $this->loadItems();

        $commonWeight = array_sum(array_map(function (CartItem $item) {
            return $item->getWeight();
        }, $this->items));

        return $commonWeight;
    }

    private function loadItems(): void
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems(): void
    {
        $this->storage->save($this->items);
    }
}
