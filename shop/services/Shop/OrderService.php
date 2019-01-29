<?php

namespace shop\services\Shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\Shop\Order\CustomersData;
use shop\entities\Shop\Order\Order;
use shop\entities\Shop\Order\OrderItem;
use shop\forms\Shop\Order\OrderForm;
use shop\repositories\Shop\DeliveryRepository;
use shop\repositories\Shop\OrderRepository;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\UserRepository;
use shop\services\TransactionManager;
use shop\entities\Shop\Order\DeliveriesData;

/**
 * OrderService - make Cart checkout
 *
 * @property Cart $cart
 * @property ProductRepository $products
 */
class OrderService
{
    private $cart;
    private $orders;
    private $products;
    private $users;
    private $deliveries;
    private $transaction;

    public function __construct(
        Cart $cart,
        OrderRepository $orders,
        ProductRepository $products,
        UserRepository $users,
        DeliveryRepository $deliveries,
        TransactionManager $transaction
    ) {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->users = $users;
        $this->deliveries = $deliveries;
        $this->transaction = $transaction;
    }

    public function checkout($userId, OrderForm $form): Order
    {
        $user = $this->users->get($userId);

        $products = [];

        $items = array_map(
            function (CartItem $item) use (&$products) {
                $product = $item->getProduct();
                $product->checkout($item->getModificationId(), $item->getQuantity());
                $products[] = $product;

                return OrderItem::create(
                    $product,
                    $item->getModificationId(),
                    $item->getPrice(),
                    $item->getQuantity()
                );
            },
            $this->cart->getItems()
        );

        $order = Order::create(
            $user->id,
            new CustomersData(
                $form->customer->name,
                $form->customer->phone
            ),
            $items,
            $this->cart->getCost()->getTotal(),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->deliveries->get($form->delivery->method),
            new DeliveriesData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->transaction->wrap(function () use ($order, $products) {
            $this->orders->save($order);

            foreach ($products as $product) {
                $this->products->save($product);
            }

            $this->cart->clear();
        });

        return $order;
    }

    /**
     * Set Order object`s status to Status::PAYMENT_PENDING
     * via object shop\entities\Shop\Order
     *
     * @param int $orderId
     * @param string $method
     * @return void
     * @throws DomainException
     */
    public function pending($orderId, $method)
    {
        /** @var Order */
        $order = $this->orders->get($orderId);

        try {
            $order->pending($method);
        } catch (\DomainException $err) {
            return $err->getMessage();
        }
    }

    /**
     * Set Order object`s status to Status::PAID
     * via object shop\entities\Shop\Order
     *
     * @param int $orderId
     * @param string $method
     * @return void
     * @throws DomainException
     */
    public function pay($orderId, $method)
    {
        /** @var Order */
        $order = $this->orders->get($orderId);

        try {
            $order->pay($method);
        } catch (\DomainException $err) {
            return $err->getMessage();
        }
    }

    /**
     * Set Order object`s status to Status::PAYMENT_FAIL
     * via object shop\entities\Shop\Order
     *
     * @param int $orderId
     * @param string $method
     * @return void
     * @throws DomainException
     */
    public function fail($orderId, $method)
    {
        /** @var Order */
        $order = $this->orders->get($orderId);

        try {
            $order->fail($method);
        } catch (\DomainException $err) {
            return $err->getMessage();
        }
    }
}
