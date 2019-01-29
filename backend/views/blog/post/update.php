<?php

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */
/* @var $model shop\forms\manage\Blog\PostForm */

$this->title = 'Обновление поста: ' . $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post->title, 'url' => ['view', 'id' => $post->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
