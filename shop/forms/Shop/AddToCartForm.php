<?php

namespace shop\forms\Shop;

use yii\helpers\ArrayHelper;
use yii\base\Model;
use shop\helpers\PriceHelper;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Modification;
use yii\helpers\StringHelper;

class AddToCartForm extends Model
{
    public $modification;
    public $quantity;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        parent::__construct($config);
        $this->_product = $product;
        $this->quantity = 1;
    }

    public function rules()
    {
        return \array_filter([
            $this->_product->modifications ? ['modification', 'required'] : false,
            ['quantity', 'required'],
            ['quantity', 'integer', 'max' => $this->_product->quantity],
        ]);
    }

    public function modificationsList(): array
    {
        return ArrayHelper::map(
            $this->_product->modifications,
            'id',
            function (Modification $modification) {
                $name = StringHelper::truncateWords($modification->name, 6);
                $price = PriceHelper::format($modification->price ?: $this->_product->price_new);

                return $modification->code . ' - ' . $name . ' ( ' . $price . ' )';
            }
        );
    }
}
