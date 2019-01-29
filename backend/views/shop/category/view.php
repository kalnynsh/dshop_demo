<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $category shop\entities\Shop\Category */

$this->title = $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $category->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $category->id], [
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
                'model' => $category,
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                    'title',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">СЕО (SEO)</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $category,
                'attributes' => [
                    'meta.title',
                    'meta.description',
                    'meta.keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>
