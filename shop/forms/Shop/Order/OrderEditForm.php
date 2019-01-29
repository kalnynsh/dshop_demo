<?php

namespace shop\forms\Shop\Order;

use shop\forms\Shop\Order\DeliveryForm;
use shop\forms\Shop\Order\CustomerForm;
use shop\forms\CompositeForm;
use shop\entities\Shop\Order\Order;

/**
 * OrderEditForm class
 *
 * @property string $note
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderEditForm extends CompositeForm
{
    public $note;

    public function __construct(Order $order, array $config = [])
    {
        $this->note = $order->note;
        $this->delivery = $this->getDeliveryForm($order);
        $this->customer = $this->getCustomerForm($order);

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['note'], 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }

    protected function getDeliveryForm(Order $order): DeliveryForm
    {
        return new DeliveryForm($order);
    }

    protected function getCustomerForm(Order $order): CustomerForm
    {
        return new CustomerForm($order);
    }
}
