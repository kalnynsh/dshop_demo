<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use shop\repositories\Shop\DeliveryRepository;
use shop\helpers\PriceHelper;
use shop\entities\Shop\Delivery;

class DeliveryHelper
{
    private $deliveries;

    public function __construct(DeliveryRepository $deliveries)
    {
        $this->deliveries = $deliveries;
    }

    public function getAllDeliveriesList()
    {
        $deliveries =  $this->deliveries->getAll();

        return ArrayHelper::map($deliveries, 'id', function (Delivery $delivery) {
            return $delivery->name
                . ', max distance: ' . DistanceHelper::format($delivery->distance)
                . ', min weight: ' . WeightHelper::format($delivery->min_weight)
                . ', max weight: ' . WeightHelper::format($delivery->max_weight)
                . ', (cost: ' . PriceHelper::format($delivery->cost) . ')';
        });
    }

    public function getDeliveriesListForWeightDistance($weight, $distance)
    {
        $deliveries
        =  $this->deliveries->getDeliveriesForWeightDistance($weight, $distance);

        return ArrayHelper::map($deliveries, 'id', function (Delivery $delivery) {
            return $delivery->name
                . ', max distance: ' . DistanceHelper::format($delivery->distance) . ', '
                . ' (cost: ' . PriceHelper::format($delivery->cost) . ')';
        });
    }

    public function getDeliveriesListForWeight($weight)
    {
        $deliveries
        =  $this->deliveries->getDeliveriesForWeight($weight);

        return ArrayHelper::map($deliveries, 'id', function (Delivery $delivery) {
            return $delivery->name
                . ', max distance: ' . DistanceHelper::format($delivery->distance) . ', '
                . ' (cost: ' . PriceHelper::format($delivery->cost) . ')';
        });
    }
}
