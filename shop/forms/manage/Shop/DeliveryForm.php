<?php

namespace shop\forms\manage\Shop;

use yii\base\Model;
use shop\services\manage\Shop\DeliveryManageService;
use shop\repositories\Shop\DeliveryRepository;
use shop\entities\Shop\Delivery;

/**
 * DeliveryForm class
 *
 * @property string $name
 * @property integer $distance
 * @property integer $minWeight
 * @property integer $maxWeight
 * @property integer $cost
 * @property integer $sort
 */
class DeliveryForm extends Model
{
    public $name;
    public $distance;
    public $minWeight;
    public $maxWeight;
    public $cost;
    public $sort;

    public function __construct(Delivery $delivery = null, $config = [])
    {
        if ($delivery) {
            $this->name = $delivery->name;
            $this->distance = $delivery->distance;
            $this->minWeight = $delivery->min_weight;
            $this->maxWeight = $delivery->max_weight;
            $this->cost = $delivery->cost;
            $this->sort = $delivery->sort;
        }

        if (!$delivery) {
            $this->sort = (new DeliveryManageService(new DeliveryRepository))
                ->getDeliveryRepository()
                ->getMaxSort()
                + 1;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'cost', 'sort'], 'required'],
            ['name', 'string', 'max' => 255],
            [
                ['distance', 'cost', 'minWeight', 'maxWeight', 'sort'],
                'integer',
            ],
        ];
    }
}
