<?php

use shop\entities\Shop\Order\Order;
use shop\helpers\OrderHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">

    <p>
        <?= Html::a(
            'Экспорт в Excel',
            ['export'],
            [
                'class' => 'btn btn-primary',
                'data-method' => 'post',
                'data-confirm' => 'Экспортируем в Excel?'
            ]
        ) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' => function (Order $model) {
                            return Html::a(
                                Html::encode($model->id),
                                ['view', 'id' => $model->id]
                            );
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Создан',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'filter' => $searchModel->statusesList(),
                        'value' => function (Order $model) {
                            return OrderHelper::statusLabel($model->current_status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class]
                ],
            ]); ?>
        </div>
    </div>

</div>
