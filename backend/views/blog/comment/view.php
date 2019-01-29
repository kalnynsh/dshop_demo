<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */
/* @var $comment shop\entities\Blog\Post\Comment */

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Комментарии', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Комментарий';
?>

<div class="comment-view">

    <p>
        <?= Html::a('Обновление',
            [
                'update',
                'post_id' => $post->id,
                'id' => $comment->id
            ],
            [
                'class' => 'btn btn-primary',
        ]) ?>

        <?php if ($comment->isActive()) : ?>

            <?= Html::a('Удаление',
                [
                    'delete',
                    'post_id' => $post->id,
                    'id' => $comment->id
                ],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить это?',
                        'method' => 'post',
                    ],
                ]
            ) ?>

        <?php else : ?>

            <?= Html::a('Активация',
                [
                    'activate',
                    'post_id' => $post->id,
                    'id' => $comment->id
                ],
                [
                    'class' => 'btn btn-success',
                ]
            ) ?>

        <?php endif; ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $comment,
                'attributes' => [
                    'id',
                    'created_at:datetime',
                    'active:boolean',
                    'user_id',
                    'parent_id',
                    [
                        'attribute' => 'post_id',
                        'vabel' => 'Пост',
                        'value' => $post->title,
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <?= \Yii::$app->formatter->asNtext($comment->text) ?>
        </div>
    </div>

</div>
