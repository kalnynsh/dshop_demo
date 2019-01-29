<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $tag shop\entities\Shop\Tag */

$this->title = $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-view">

    <p>
        <?= Html::a('Обновление', ['update', 'id' => $tag->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удаление', ['delete', 'id' => $tag->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $tag,
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                ],
            ]) ?>
        </div>
    </div>
</div>
