<?php

namespace shop\forms\Shop\Order;

use shop\forms\Shop\Order\DeliveryForm;
use shop\forms\Shop\Order\CustomerForm;
use shop\forms\CompositeForm;

/**
 * OrderForm class
 *
 * @property string $note
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderForm extends CompositeForm
{
    public $note;

    public function __construct(array $config = [])
    {
        $this->delivery = $this->createDeliveryForm();
        $this->customer = $this->createCustomerForm();

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['note'], 'string'],
        ];
    }

    protected function createDeliveryForm(): DeliveryForm
    {
        return new DeliveryForm();
    }

    protected function createCustomerForm(): CustomerForm
    {
        return new CustomerForm();
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }

    public function attributeLabels(): array
    {
        return [
            'note' => 'Примечания (пожелания)',
        ];
    }
}
