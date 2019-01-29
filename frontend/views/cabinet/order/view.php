<?php

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\DetailView;
use yii\helpers\Html;
use shop\helpers\PriceHelper;
use shop\helpers\OrderHelper;

$this->title = 'Заказ ID: ' . $order->id;
$this->params['breadcrumbs'][] = [
    'label' => 'Личный кабинет',
    'url' => ['cabinet/default/index'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Заказы',
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cabinet-order-view">

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'label' => 'Заказ ID',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата создания',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'current_status',
                        'label' => 'Текущий статус заказа',
                        'value' => OrderHelper::statusLabel($order->current_status),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'customer_name',
                        'label' => 'Покупатель',
                    ],

                    [
                        'attribute' => 'delivery_name',
                        'label' => 'Способ доставки',
                    ],
                    [
                        'attribute' => 'deliveriesData.index',
                        'label' => 'Почтовый индекс доставки',
                    ],
                    [
                        'attribute' => 'deliveriesData.address',
                        'label' => 'Адрес доставки',
                    ],
                    [
                        'attribute' => 'cost',
                        'label' => 'Стоимость доставки',
                        'value' => (PriceHelper::format($order->cost)
                            . '&nbsp;<i class="fa fa-rub"></i>'),
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'note',
                        'label' => 'Примечания (пожелания)',
                        'format' => 'ntext',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left">Код,<br/> наименование</th>
                            <th class="text-left">Данные<br/> о модификации</th>
                            <th class="text-center">Кол-во</th>
                            <th class="text-center">Стоимость<br/> единицы</th>
                            <th class="text-center">Итого:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order->items as $item) : ?>
                            <tr>
                                <td class="text-left">
                                    <?= Html::encode($item->product_code) ?><br/>
                                    <?= Html::encode($item->product_name) ?>
                                </td>
                                <?php if ($item->modification_id) : ?>
                                    <td class="text-left">
                                        <?= Html::encode($item->modification_code) ?>
                                        <?= Html::encode($item->modification_name) ?>
                                    </td>
                                <?php else : ?>
                                    <td class="text-left">
                                        У товара нет модификаций
                                    </td>
                                <?php endif ?>
                                <td class="text-center">
                                    <?= Html::encode($item->quantity) ?>
                                </td>
                                <td class="text-center">
                                    <?= PriceHelper::format($item->price) ?>
                                    &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                                </td>
                                <td class="text-center">
                                    <?= PriceHelper::format($item->getCost()) ?>
                                    &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($order->canBePaid()) : ?>
                <p>
                    <?= Html::a(
                        'Оплатить через платежную систему Робокасса',
                        ['/payment/robokassa/invoice', 'id' => $order->id],
                        ['class' => 'btn btn-success']
                    ) ?>
                </p>
            <?php endif ?>

            <?php if ($order->canBePaid()
                && Yii::$app->get('robokassa')->isTest) : ?>
                <p>
                    <?= Html::a(
                        'Тест оплаты через платежную систему Робокасса',
                        ['/payment/robokassa/invoice-test', 'id' => $order->id],
                        ['class' => 'btn btn-success']
                    ) ?>
                </p>
            <?php endif ?>
        </div>
    </div>

</div>
