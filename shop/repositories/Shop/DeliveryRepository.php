<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Delivery;
use shop\repositories\NotFoundException;

class DeliveryRepository
{
    public function get($id): Delivery
    {
        if (!$method = Delivery::findOne($id)) {
            throw new NotFoundException('Delivery method not found.');
        }

        return $method;
    }

    public function findByName($name): ?Delivery
    {
        return Delivery::findOne(['name' => $name]);
    }

    public function save(Delivery $method): void
    {
        if (!$method->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Delivery $method): void
    {
        if (!$method->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function getMaxSort(): ?int
    {
        return Delivery::find()->max('sort');
    }


    public function getAll(): ?array
    {
        return Delivery::find()
            ->orderBy('sort')
            ->all();
    }

    public function getAllasArray(): ?array
    {
        return Delivery::find()
            ->orderBy('sort')
            ->asArray()
            ->all();
    }

    public function getDeliveriesForWeightDistance($weight, $distance): ?array
    {
        return Delivery::find()
            ->availableForWeight($weight)
            ->availableForDistance($distance)
            ->orderBy('sort')
            ->all();
    }

    public function getDeliveriesForWeight($weight): ?array
    {
        return Delivery::find()
            ->availableForWeight($weight)
            ->orderBy('sort')
            ->all();
    }
}
