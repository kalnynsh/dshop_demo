<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Blog\Post\PostForm */

$this->title = 'Создание поста';
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
