<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $cartForm \shop\forms\Shop\AddToCartForm */


$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = ['label' => 'Моя корзина', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title . ' в корзину';
?>
<div class="shop-cart-add">
    <h1><?= Html::encode($this->title . ' в корзину') ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin() ?>

            <?php if ($modifications = $cartForm->modificationsList()) : ?>
                <?= $form
                    ->field($cartForm, 'modification')
                    ->dropDownList(
                        $modifications,
                        ['prompt' => '--- Выбрать модификацию ---']
                    );
                ?>
            <?php endif; ?>

            <?= $form->field($cartForm, 'quantity')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(
                    'Добавить в корзину',
                    ['class' => 'btn btn-primary btn-lg btn-block']
                ) ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>

</div>
