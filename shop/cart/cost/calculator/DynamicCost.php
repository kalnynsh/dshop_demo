<?php

namespace shop\cart\cost\calculator;

use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\cost\Discount as CartDiscount;
use shop\entities\Shop\Discount as DiscountEntity;
use shop\repositories\Shop\DiscountRepository;
use shop\cart\cost\Cost;

class DynamicCost implements CalculatorInterface
{
    private $calculator;
    private $repository;

    public function __construct(CalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
        $this->repository = new DiscountRepository();
    }

    public function getCost(array $items): Cost
    {
        /** @var DiscountEntity[] $discounts */
        $discounts = $this->repository->findAll();

        /** @var Cost $cost */
        $cost = $this->calculator->getCost($items);

        foreach ($discounts as $discount) {
            if ($discount->isEnabled()) {
                $new = $this->createCartDiscount($cost, $discount);
                $cost = $cost->withDiscount($new);
            }
        }

        return $cost;
    }

    private function createCartDiscount(
        Cost $cost,
        DiscountEntity $discount
    ): CartDiscount {
        return new CartDiscount(
            $cost->getOrigin() * ($discount->percent / 100),
            $discount->name
        );
    }
}
