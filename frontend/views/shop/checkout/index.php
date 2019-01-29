<?php

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use shop\helpers\PriceHelper;
use shop\helpers\WeightHelper;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\Shop\Order\OrderForm */

$this->title = 'Оформление покупки';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['/shop/cart/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cart-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="text-left">Наименование</td>
                    <td class="text-left">Наименование модификации</td>
                    <td class="text-center">Кол-во</td>
                    <td class="text-center">Цена<br/> за единицу</td>
                    <td class="text-center">Всего</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart->getItems() as $item) : ?>
                    <?php
                        $product = $item->getProduct();
                        $modification = $item->getModification();
                        $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                    ?>
                    <tr>
                        <td class="text-left">
                            <a href="<?= $url ?>">
                                <?= Html::encode(StringHelper::truncateWords($product->name, 20)) ?>
                            </a>
                        </td>
                        <td class="text-left">
                            <?php if ($modification) : ?>
                                <?= Html::encode($modification->name) ?>
                            <?php else : ?>
                                <p>Модификации отсутствуют.</p>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?= $item->getQuantity() ?>
                        </td>
                        <td class="text-center">
                            <?= PriceHelper::format($item->getPrice()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                        <td class="text-center">
                            <?= PriceHelper::format($item->getCost()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <br />

        <?php $cost = $cart->getCost() ?>
        <table class="table table-bordered">
            <tr>
                <td class="text-right"><strong>Под итог:</strong></td>
                <td class="text-right">
                    <?= PriceHelper::format($cost->getOrigin()) ?>
                    &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                </td>
            </tr>
            <tr>
                <td class="text-right"><strong>Итого:</strong></td>
                <td class="text-right">
                    <?= PriceHelper::format($cost->getTotal()) ?>
                    &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                </td>
            </tr>
            <tr>
                <td class="text-right"><strong>Общий вес:</strong></td>
                <td class="text-right"><?= WeightHelper::format($cart->getWeight()) ?></td>
            </tr>
        </table>

        <?php $form = ActiveForm::begin() ?>

            <div class="panel panel-default">
                <div class="panel-heading">Покупатель</div>
                <div class="panel-body">
                    <?= $form->field($model->customer, 'name')->textInput() ?>
                    <?= $form->field($model->customer, 'phone')->textInput() ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Доставка</div>
                <div class="panel-body">
                    <p>Согласно весу покупок
                        (<?= WeightHelper::format($cart->getWeight()) ?>)
                        и расстоянию выберите доставку.
                    </p>
                    <?= $form->field($model->delivery, 'method')->dropDownList(
                        $model->delivery->list(),
                        ['prompt' => '--- Выбор ---']
                    ) ?>
                    <?= $form->field($model->delivery, 'index')->textInput() ?>
                    <?= $form->field($model->delivery, 'address')->textarea(['rows' => 3]) ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Примечания</div>
                <div class="panel-body">
                    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Купить', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
            </div>

        <?php ActiveForm::end() ?>
</div>
