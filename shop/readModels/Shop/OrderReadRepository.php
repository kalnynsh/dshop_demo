<?php

namespace shop\readModels\Shop;

use yii\data\ActiveDataProvider;
use shop\services\manage\Shop\OrderManageService;
use shop\entities\Shop\Order\Order;

class OrderReadRepository
{
    private $service;

    public function __construct(OrderManageService $service)
    {
        $this->service = $service;
    }

    public function getDataProvider($userId): ActiveDataProvider
    {
        return $this->getActiveDataProvider([
            'query' => $this
                ->service
                ->queryOrdersByUserId($userId)
                ->orderBy([
                    'id' => SORT_DESC,
                ]),
            'sort' => false,
        ]);
    }

    public function findOneOrder($userId, $orderId): ?Order
    {
        return $this->service->findOneOrder($userId, $orderId);
    }

    private function getActiveDataProvider(array $data): ActiveDataProvider
    {
        return new ActiveDataProvider($data);
    }
}
