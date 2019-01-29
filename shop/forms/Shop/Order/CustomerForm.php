<?php

namespace shop\forms\Shop\Order;

use yii\base\Model;
use shop\entities\Shop\Order\Order;

class CustomerForm extends Model
{
    public $name;
    public $phone;

    public function __construct(Order $order = null, array $config = [])
    {
        if ($order) {
            $this->name = $order->customersData->name;
            $this->phone = $order->customersData->phone;
        }

        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['name', 'phone'], 'required'],
            [['name', 'phone'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Фамилия Имя Отчество',
            'phone' => 'Телефон',
        ];
    }
}
