<?php

namespace shop\forms\Shop\Order;

use yii\base\Model;
use shop\repositories\Shop\DeliveryRepository;
use shop\helpers\DeliveryHelper;
use shop\entities\Shop\Order\Order;

class DeliveryForm extends Model
{
    public $method;
    public $index;
    public $address;

    public function __construct(Order $order = null, array $config = [])
    {
        if ($order) {
            $this->method = $order->delivery_id;
            $this->index = $order->deliveriesData->index;
            $this->address = $order->deliveriesData->address;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['method'], 'integer'],
            [['index', 'address'], 'required'],
            [['index'], 'string', 'max' => 255],
            [['address'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'method' => 'ID доставки',
            'index' => 'Почтовый индекс',
            'address' => 'Адрес',
        ];
    }

    public function list(): array
    {
        return $this->getDeliveryHelper()->getAllDeliveriesList();
    }

    private function getDeliveryHelper(): DeliveryHelper
    {
        return new DeliveryHelper(new DeliveryRepository);
    }
}
