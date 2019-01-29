<?php

use shop\entities\Shop\Characteristic;
use shop\helpers\CharacteristicHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CharacteristicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Характеристики';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="сharacteristics-index">

    <p>
        <?= Html::a('Добавить характеристику', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => 'Наименование',
                        'value' => function (Characteristic $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'label' => 'Тип (строковый, целый, дестичный)',
                        'filter' => $searchModel->typesList(),
                        'value' => function (Characteristic $model) {
                            return CharacteristicHelper::typeName($model->type);
                        },
                    ],
                    [
                        'attribute' => 'required',
                        'label' => 'Обязателен (да/нет)',
                        'filter' => $searchModel->requiredList(),
                        'format' => 'boolean',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
