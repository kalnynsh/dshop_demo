<?php

use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $model shop\forms\manage\Shop\Product\QuantityForm */

$productName = StringHelper::truncateWords($product->name, 6);
$this->title = 'Количество товара - ' . $productName;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $productName, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Количество';
?>

<div class="product-quantity">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Количество</div>
        <div class="box-body">
            <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
