<?php

use shop\entities\Shop\Category;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="сategories-index">

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => 'Категория',
                        'value' => function (Category $model) {
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;', $model->depth - 1) . ' ' : '');
                            return $indent . Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function (Category $model) {
                            return
                                Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', ['move-up', 'id' => $model->id]) .
                                Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', ['move-down', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => 'Слаг',
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Наименование',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
