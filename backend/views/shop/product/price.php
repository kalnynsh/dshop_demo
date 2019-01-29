<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $model shop\forms\manage\Shop\Product\PriceForm */

$productName = StringHelper::truncateWords($product->name, 3);
$this->title = 'Цена для товара: ' . $productName;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $productName, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Price';
?>

<div class="product-price">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Цены</div>
        <div class="box-body">
            <?= $form->field($model, 'new')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'old')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
