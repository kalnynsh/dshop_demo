<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use shop\entities\Shop\queries\DeliveryQuery;

/**
 * Delivery entity class
 *
 * @property int $id
 * @property string $name
 * @property integer $distance
 * @property integer $min_weight
 * @property integer $max_weight
 * @property integer $cost
 * @property integer $sort
 */
class Delivery extends ActiveRecord
{
    public static function create(
        $name,
        $distance,
        $minWeight,
        $maxWeight,
        $cost,
        $sort
    ): self {
        $method = new static();
        $method->name = $name;
        $method->distance = $distance;
        $method->min_weight = $minWeight;
        $method->max_weight = $maxWeight;
        $method->cost = $cost;
        $method->sort = $sort;

        return $method;
    }

    public function edit(
        $name,
        $distance,
        $minWeight,
        $maxWeight,
        $cost,
        $sort
    ): void {
        $this->name = $name;
        $this->distance = $distance;
        $this->min_weight = $minWeight;
        $this->max_weight = $maxWeight;
        $this->cost = $cost;
        $this->sort = $sort;
    }

    public static function tableName(): string
    {
        return '{{%shop_deliveries}}';
    }

    public static function find(): DeliveryQuery
    {
        return new DeliveryQuery(static::class);
    }
}
