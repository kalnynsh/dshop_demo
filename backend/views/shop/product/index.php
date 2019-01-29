<?php

use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">

    <p>
        <?= Html::a('Добавить Товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function (Product $model) {
                    return $model->quantity <= 0 ? ['style' => 'background: #FDC'] : [];
                },
                'columns' => [
                    [
                        'label' => 'Изображение',
                        'value' => function (Product $model) {
                            return $model->mainPhoto ?
                                Html::img($model->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    'id',
                    [
                        'attribute' => 'name',
                        'label' => 'Наименование',
                        'value' => function (Product $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'category_id',
                        'label' => 'Категория',
                        'filter' => $searchModel->categoriesList(),
                        'value' => 'category.name',
                    ],
                    [
                        'attribute' => 'price_new',
                        'label' => 'Новая цена',
                        'value' => function (Product $model) {
                            return PriceHelper::format($model->price_new);
                        },
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => 'Кол-во',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Product $model) {
                            return ProductHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
