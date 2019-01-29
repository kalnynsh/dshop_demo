<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use shop\helpers\PriceHelper;
use shop\helpers\OrderHelper;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Order\Order */

$this->title = 'Заказ ID: ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $order->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $order->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эти данные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID заказа',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата и время создания заказа',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'current_status',
                        'label' => 'Текущий статус',
                        'value' => OrderHelper::statusLabel($order->current_status),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'user_id',
                        'label' => 'ID покупателя',
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
                        'label' => 'Почтовый индекс адреса доставки',
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
                        'label' => 'Заметки покупателя о доставке',
                        'format' => 'ntext',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom: 0">
                    <thead>
                        <tr>
                            <th class="text-left">Код и наименование</th>
                            <th class="text-left">Данные модификации <br/>(при наличии)</th>
                            <th class="text-center">Кол-во</th>
                            <th class="text-center">Цена за ед.</th>
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
                                        <?= Html::encode($item->modification_code) ?><br/>
                                        <?= Html::encode($item->modification_name) ?>
                                    </td>
                                <?php else : ?>
                                    <td class="text-left">
                                        У товара нет модификации
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
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom: 0">
                    <thead>
                        <tr>
                            <th class="text-center">№ п/п</th>
                            <th class="text-left">Дата создания Заказа</th>
                            <th class="text-left">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $idx = 1 ?>
                        <?php foreach ($order->statuses as $status) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $idx++ ?>
                                </td>
                                <td class="text-left">
                                    <?= \Yii::$app->formatter->asDatetime($status->created_at) ?>
                                </td>
                                <td class="text-left">
                                    <?= OrderHelper::statusLabel($status->value) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
