<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $method shop\entities\Shop\Delivery */

$this->title = $method->name;

$this->params['breadcrumbs'][] = [
    'label' => 'Способы доставки',
    'url' => ['index'],
];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="delivery-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $method->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $method->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эти данные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $method,
                'attributes' => [
                    'id',
                    'name',
                    'distance',
                    'min_weight',
                    'max_weight',
                    'cost',
                ],
            ]) ?>
        </div>
    </div>

</div>
