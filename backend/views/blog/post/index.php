<?php

use shop\entities\Blog\Post\Post;
use yii\helpers\Html;
use yii\grid\GridView;
use shop\helpers\Blog\PostHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Blog\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <p>
        <?= Html::a('Добавить пост', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'photo',
                        'label' => 'Фото',
                        'value' => function (Post $model) {
                            return $model->photo ? Html::img($model->getThumbFileUrl('photo', 'admin')) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    'id',
                    'created_at:datetime',
                    [
                        'attribute' => 'title',
                        'value' => function (Post $model) {
                            return
                                Html::a(
                                    Html::encode($model->title),
                                    [
                                        'view',
                                        'id' => $model->id
                                    ]
                                );
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: left'],
                    ],
                    [
                        'attribute' => 'category_id',
                        'filter' => $searchModel->categoriesList(),
                        'value' => 'category.name',
                    ],
                    'content:html',
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Post $model) {
                            return PostHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
