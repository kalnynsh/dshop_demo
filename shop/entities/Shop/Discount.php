<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use shop\entities\Shop\queries\DiscountQuery;
use shop\services\DateTimeService;

/**
 * @property integer $percent
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property bool $active
 * @property integer $sort
 */
class Discount extends ActiveRecord
{
    private $dateTime;

    public function __construct()
    {
        $this->dateTime = new DateTimeService();
    }

    public static function create(
        $percent,
        $name,
        $fromDate,
        $toDate,
        $sort
    ): self {
        $discount = new static();
        $discount->percent = $percent;
        $discount->name = $name;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->sort = $sort;
        $discount->active = true;

        return $discount;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function draft(): void
    {
        $this->active = false;
    }

    public function isEnabled(): bool
    {
        return $this->dateTime::checkDateInRange(
            $this->from_date,
            $this->to_date,
            'now'
        );
    }

    public static function tableName(): string
    {
        return '{{%shop_discounts}}';
    }

    public static function find(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }
}
