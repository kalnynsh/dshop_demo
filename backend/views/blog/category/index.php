<?php

use shop\entities\Blog\Category;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Blog\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории блога';
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
                    'sort',
                    [
                        'attribute' => 'name',
                        'label' => 'Категория',
                        'value' => function (Category $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'slug',
                    'title',
                    'description:html',
                    [
                        'value' => function (Category $model) {
                            return
                                Html::a(
                                    '<span class="fa fa-arrow-circle-up"></span>',
                                    ['move-up', 'id' => $model->id]
                                )
                                . '&nbsp;&nbsp;'
                                . Html::a(
                                    '<span class="fa fa-arrow-circle-down"></span>',
                                    ['move-down', 'id' => $model->id]
                                );
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
