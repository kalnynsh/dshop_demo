<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use shop\helpers\WeightHelper;
use shop\helpers\DistanceHelper;
use shop\entities\Shop\Delivery;

/** @var $this yii\web\View */
/** @var $searchModel backend\forms\Shop\DeliverySearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Способы доставки';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="delivery-index">

    <p>
        <?= Html::a(
            'Добавить новый способ доставки',
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'name',
                        'label' => 'Наименование',
                        'value' => function (Delivery $model) {
                            return Html::a(
                                Html::encode($model->name),
                                ['view', 'id' => $model->id]
                            );
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'distance',
                        'label' => 'Максимальное расстояние',
                        'value' => function (Delivery $model) {
                            return DistanceHelper::format($model->distance);
                        }
                    ],
                    [
                        'attribute' => 'min_weight',
                        'label' => 'Мин. вес',
                        'value' => function (Delivery $model) {
                            return WeightHelper::format($model->min_weight);
                        }
                    ],
                    [
                        'attribute' => 'max_weight',
                        'label' => 'Макс. вес',
                        'value' => function (Delivery $model) {
                            return WeightHelper::format($model->max_weight);
                        }
                    ],
                    [
                        'attribute' => 'cost',
                        'label' => 'Стоимость',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]) ?>
        </div>
    </div>
</div>
