<?php

namespace shop\cart\cost;

use shop\cart\cost\Discount;

/**
 * Cost class
 *
 * @property float $value
 * @property array $discounts
 */
final class Cost
{
    private $value;
    private $discounts = [];

    public function __construct(float $value, array $discounts = [])
    {
        $this->value = $value;
        $this->discounts = $discounts;
    }

    public function withDiscount(Discount $discount): self
    {
        return new static(
            $this->value,
            array_merge(
                $this->discounts,
                [$discount]
            )
        );
    }

    public function getOrigin(): float
    {
        return $this->value;
    }

    public function getTotal(): float
    {
        return $this->value - $this->calculateDiscount();
    }

    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    private function calculateDiscount(): float
    {
        return array_sum(
            array_map(function (Discount $discount) {
                return $discount->getValue();
            }, $this->discounts)
        );
    }
}
