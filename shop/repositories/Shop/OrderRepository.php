<?php

namespace shop\repositories\Shop;

use yii\db\ActiveQuery;
use shop\repositories\NotFoundException;
use shop\entities\Shop\Order\Order;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException('Order not found');
        }

        return $order;
    }

    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function query(): ActiveQuery
    {
        return Order::find();
    }

    public function findOneOrder($userId, $id): ?Order
    {
        return $this->query()->andWhere([
            'user_id' => $userId,
            'id' => $id
        ])
        ->one();
    }

    public function queryOrdersByUserId($userId): ActiveQuery
    {
        return $this->query()->andWhere(['user_id' => $userId]);
    }
}
