<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $brand shop\entities\Shop\Brand */

$this->title = $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-view">

    <p>
        <?= Html::a('Обновление', ['update', 'id' => $brand->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удаление', ['delete', 'id' => $brand->id], [
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
                'model' => $brand,
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">СЕО (SEO)</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $brand,
                'attributes' => [
                    'meta.title',
                    'meta.description',
                    'meta.keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>
