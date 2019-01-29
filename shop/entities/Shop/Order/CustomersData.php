<?php

namespace shop\entities\Shop\Order;

class CustomersData
{
    public $phone;
    public $name;

    public function __construct($name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }
}
