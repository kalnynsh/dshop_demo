<?php

namespace shop\cart\cost;

/**
 * Discount class
 *
 * @property float $value
 * @property string $name
 */
final class Discount
{
    private $value;
    private $name;

    public function __construct(float $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
