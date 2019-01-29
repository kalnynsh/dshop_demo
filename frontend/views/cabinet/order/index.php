<?php

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use shop\helpers\OrderHelper;
use shop\entities\Shop\Order\Order;

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = [
    'label' => 'Личный кабинет',
    'url' => ['cabinet/default/index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-order-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID заказа',
                        'value' => function (Order $order) {
                            return Html::a(Html::encode($order->id), ['view', 'id' => $order->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата создания',
                        'value' => function (Order $order) {
                            return Html::a(
                                Html::encode(
                                    \Yii::$app->formatter->asDatetime($order->created_at)
                                ),
                                ['view', 'id' => $order->id]
                            );
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус заказа',
                        'value' => function (Order $order) {
                            return OrderHelper::statusLabel($order->current_status);
                        },
                        'format' => 'raw',
                    ],
                ]
            ]) ?>
        </div>
    </div>

</div>
