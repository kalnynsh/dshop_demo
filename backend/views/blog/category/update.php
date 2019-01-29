<?php

/* @var $this yii\web\View */
/* @var $category shop\entities\Blog\Category */
/* @var $model shop\forms\manage\Blog\CategoryForm */

$this->title = 'Обновление категории: ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
