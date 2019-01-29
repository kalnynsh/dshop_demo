<?php

/* @var $this yii\web\View */
/* @var $page shop\entities\Content\Page */
/* @var $model shop\forms\manage\Content\PageForm */

$this->title = 'Обновить страницу: ' . $page->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $page->title, 'url' => ['view', 'id' => $page->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
