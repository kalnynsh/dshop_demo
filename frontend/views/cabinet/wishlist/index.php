<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Список желаний';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Изображение',
                'value' => function (Product $product) {
                    return $product->mainPhoto ?
                        Html::img($product->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 100px'],
            ],
            [
                'attribute' => 'code',
                'label' => 'Код',
                'value' => function (Product $product) {
                    return $product->code;
                },
            ],
            [
                'attribute' => 'name',
                'label' => 'Наименование',
                'value' => function (Product $product) {
                    return Html::a(Html::encode($product->name), [
                        '/shop/catalog/product',
                        'id' => $product->id,
                    ]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'price_new',
                'label' => 'Новая цена',
                'value' => function (Product $product) {
                    return PriceHelper::format($product->price_new);
                },
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
