<?php

namespace frontend\widgets\Shop;

use yii\base\Widget;
use shop\services\Shop\CartService;

class CartWidget extends Widget
{
    /** @property CartService $service */
    private $service;

    public function __construct(CartService $service, $config = [])
    {
        parent::__construct($config);
        $this->service = $service;
    }

    public function run(): string
    {
        return $this->render('cart', [
            'service' => $this->service,
        ]);
    }
}
