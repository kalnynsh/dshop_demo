<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property integer $price
 * @property integer $quantity
 */
class Modification extends ActiveRecord
{
    public static function create($code, $name, $price, $quantity): self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;
        $modification->quantity = $quantity;

        return $modification;
    }

    public function edit($code, $name, $price, $quantity): void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public function isCodeEqualTo($code): bool
    {
        return $this->code === $code;
    }

    public function checkout($quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new \DomainException(
                'Only ' . $this->quantity . ' items available.'
            );
        }

        $this->quantity -= $quantity;
    }

    public static function tableName(): string
    {
        return '{{%shop_modifications}}';
    }
}
