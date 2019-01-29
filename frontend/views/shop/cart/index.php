<?php

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use shop\helpers\PriceHelper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\Shop\AddToCartForm */


$this->title = 'Мои покупки';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="text-center td-image">Изображение</td>
                    <td class="text-left">Наименование</td>
                    <td class="text-left">Наименование модификации</td>
                    <td class="text-left">Количество</td>
                    <td class="text-right">Цена за единицу</td>
                    <td class="text-right">Всего</td>
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
                        <td class="text-center">
                        <?php if ($product->mainPhoto) : ?>
                            <a href="<?= $url ?>">
                                <img src="<?=$product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>"
                                    alt="Product image" class="img-thumbnail"
                                />
                            </a>
                        <?php endif; ?>
                        </td>
                        <td class="text-left">
                            <a href="<?= $url ?>">
                                <?= Html::encode(StringHelper::truncateWords($product->name, 20)) ?>
                            </a>
                        </td>
                        <td class="text-left">
                            <?php if ($modification) : ?>
                                <?= Html::encode($modification->name) ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-left">
                            <?= Html::beginForm([
                                    'quantity',
                                    'id' => $item->getId()
                                ],
                                'post'
                            ) ?>
                                <div class="intup-group btn-block cart-btns">
                                    <input type="text" name="quantity"
                                        value="<?= $item->getQuantity() ?>"
                                        size="1"
                                        class="form-control"
                                    />
                                    <span class="input-group-btn cart-group-btn">
                                        <button type="submit" title="" class="btn btn-primary"
                                            data-original-title="Update"
                                        >
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <a title="Remove" class="btn btn-danger"
                                            href="<?= Url::to(['remove', 'id' => $item->getId()]) ?>"
                                            data-method="post"
                                        >
                                            <i class="fa fa-times-circle"></i>
                                        </a>
                                    </span>
                                </div>
                            <?= Html::endForm() ?>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($item->getPrice()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($item->getCost()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <br />
    <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
            <?php $cost = $cart->getCost() ?>
            <table class="table table-bordered">
                <tr>
                    <td class="text-right"><strong>Под итог:</strong></td>
                    <td class="text-right">
                        <?= PriceHelper::format($cost->getOrigin()) ?>
                        &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                    </td>
                </tr>
                <?php foreach ($cost->getDiscounts() as $discount) : ?>
                    <tr>
                        <td class="text-right"><strong><?= Html::encode($discount->getName()) ?>:</strong></td>
                        <td class="text-right">
                            <?= PriceHelper::format($discount->getValue()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                        <td class="text-right"><strong>Всего:</strong></td>
                        <td class="text-right">
                            <?= PriceHelper::format($cost->getTotal()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="buttons clearfix">
        <div class="pull-left">
            <a href="<?= Url::to(['/shop/catalog/index']) ?>" class="btn btn-default">
                Продолжить
            </a>
            <?php if ($cart->getItems()) : ?>
                <div class="pull-right">
                    <a href="<?= Url::to('/shop/checkout/index') ?>" class="btn btn-primary">
                        Купить
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
