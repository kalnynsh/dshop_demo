<?php

use yii\helpers\StringHelper;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use shop\entities\Blog\Post\Comment;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Blog\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comments-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'created_at:datetime',
                    [
                        'attribute' => 'text',
                        'value' => function (Comment $model) {
                            return StringHelper::truncateWords(strip_tags($model->text), 15);
                        },
                    ],
                    [
                        'attribute' => 'active',
                        'filter' => $searchModel->activeList(),
                        'format' => 'boolean',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
