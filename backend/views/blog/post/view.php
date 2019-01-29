<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use shop\helpers\Blog\PostHelper;

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-view">

    <p>
        <?php if ($post->isActive()) : ?>
            <?= Html::a(
                'В черновик',
                ['draft', 'id' => $post->id],
                ['class' => 'btn btn-default', 'data-method' => 'post']
            ) ?>
        <?php else : ?>
            <?= Html::a(
                'Активировать',
                ['activate', 'id' => $post->id],
                ['class' => 'btn btn-success', 'data-method' => 'post']
            ) ?>
        <?php endif; ?>
        <?= Html::a('Обновить', ['update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $post->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эти данные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Общие данные</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $post,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'status',
                        'value' => PostHelper::statusLabel($post->status),
                        'format' => 'raw',
                    ],
                    'title',
                    [
                        'attribute' => 'category_id',
                        'value' => ArrayHelper::getValue($post, 'category.name'),
                    ],
                    [
                        'label' => 'Теги',
                        'value' => implode(', ', ArrayHelper::getColumn($post->tags, 'name')),
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Фото</div>
        <div class="box-body">
            <?php if ($post->photo) : ?>
                <?= Html::a(
                    Html::img($post->getThumbFileUrl('photo', 'thumb')),
                    $post->getUploadedFileUrl('photo'),
                    [
                        'class' => 'thumbnail',
                        'target' => '_blank'
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Краткое описание</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asNtext($post->description) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Содержание</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asHtml($post->content) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">СЕО (SEO)</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $post,
                'attributes' => [
                    'meta.title',
                    'meta.description',
                    'meta.keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>
