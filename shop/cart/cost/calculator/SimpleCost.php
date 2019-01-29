<?php

namespace shop\cart\cost\calculator;

use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\cost\Cost;

class SimpleCost implements CalculatorInterface
{
    public function getCost(array $items): Cost
    {
        $cost = 0;

        foreach ($items as $item) {
            $cost += $item->getCost();
        }

        return $this->createCost($cost);
    }

    private function createCost($cost): Cost
    {
        return new Cost($cost);
    }
}
