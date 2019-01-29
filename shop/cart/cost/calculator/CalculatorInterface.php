<?php

namespace shop\cart\cost\calculator;

use shop\cart\cost\Cost;
use shop\cart\CartItem;

interface CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return Cost
     */
    public function getCost(array $items): Cost;
}
