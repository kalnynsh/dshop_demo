<?php

namespace shop\forms\manage\Shop\Product;

use yii\base\Model;
use shop\entities\Shop\Product\Product;

class QuantityForm extends Model
{
    public $quantity;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->quantity = (int)$product->quantity;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['quantity', 'required'],
            ['quantity', 'integer', 'min' => 0],
        ];
    }
}
