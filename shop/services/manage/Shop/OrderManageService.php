<?php

namespace shop\services\manage\Shop;

use yii\db\ActiveQuery;
use shop\repositories\Shop\OrderRepository;
use shop\repositories\Shop\DeliveryRepository;
use shop\forms\Shop\Order\OrderEditForm;
use shop\entities\Shop\Order\Order;
use shop\entities\Shop\Order\DeliveriesData;
use shop\entities\Shop\Order\CustomersData;
use shop\repositories\NotFoundException;

/**
 * OrderService class
 *
 * @property OrderRepository $orders
 * @property DeliveryRepository $deliveries
 */
class OrderManageService
{
    private $orders;
    private $deliveries;

    public function __construct(
        OrderRepository $orders,
        DeliveryRepository $deliveries
    ) {
        $this->orders = $orders;
        $this->deliveries = $deliveries;
    }

    public function edit($id, OrderEditForm $form): void
    {
        /** @var $order Order */
        $order = $this->get($id);

        $order->edit(
            $this->getCustomersData(
                $form->customer->name,
                $form->customer->phone
            ),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->deliveries->get($form->delivery->method),
            $this->getDeliveriesData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->get($id);
        $this->orders->remove($order);
    }

    public function getOrderRepository(): OrderRepository
    {
        return $this->orders;
    }

    /**
     * Get Orger class object by $orderId
     *
     * @param int $orderId
     * @return Order
     * @throws NotFoundException
     */
    public function get($orderId): Order
    {
        return $this->orders->get($orderId);
    }

    private function getCustomersData($name, $phone): CustomersData
    {
        return new CustomersData($name, $phone);
    }

    private function getDeliveriesData($index, $address): DeliveriesData
    {
        return new DeliveriesData($index, $address);
    }

    public function getQuery(): ActiveQuery
    {
        return $this->orders->query();
    }

    public function findOneOrder($userId, $id): ?Order
    {
        return $this->orders->findOneOrder($userId, $id);
    }

    public function queryOrdersByUserId($userId): ActiveQuery
    {
        return $this->orders->queryOrdersByUserId($userId);
    }
}
