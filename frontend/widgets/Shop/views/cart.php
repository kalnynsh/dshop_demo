<?php

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use shop\helpers\PriceHelper;

/** @var $service shop\services\Shop\CartService */
?>

<div id="cart" class="btn-group btn-block">
    <button type="button" data-toggle="dropdown" data-loading-text="Загрузка..."
    class="btn btn-inverse btn-block btn-lg dropdown-toggle">
        <i class="fa fa-shopping-cart"></i>
        <span id="cart-total">
            <?= $service->getAmount() ?>
            покупка(и), на сумму - <?= PriceHelper::format(
                $service->getTotal()
            ) ?>
            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
        </span>
    </button>
    <ul class="dropdown-menu pull-right">
        <?php if ($service->getAmount() == 0) : ?>
        <li>
            <p class="text-center">Ваша корзина пуста!</p>
        </li>
        <?php else : ?>
        <li>
            <table class="table table-striped">
                <?php foreach ($service->getItems() as $item) : ?>
                    <?php
                        $product = $item->getProduct();
                        $modification = $item->getModification();
                        $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                    ?>
                    <tr>
                        <td class="text-center">
                        <?php if ($product->mainPhoto) : ?>
                            <img src="<?=$product->mainPhoto->getThumbFileUrl('file', 'cart_widget_list') ?>"
                                alt="Product image" class="img-thumbnail"
                            />
                        <?php endif; ?>
                        </td>
                        <td class="text-left">
                            <a href="<?= $url ?>">
                                <?= Html::encode(StringHelper::truncateWords($product->name, 4)) ?>
                            </a>
                            <?php if ($modification) : ?>
                                <br />
                                <small>
                                    <?= Html::encode(StringHelper::truncateWords($modification->name, 4)) ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            x <?= $item->getQuantity() ?>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($item->getCost()) ?>
                        </td>
                        <td class="text-center">
                            <a title="Remove" class="btn btn-danger btn-xs"
                                href="<?= Url::to(['/shop/cart/remove', 'id' => $item->getId()]) ?>"
                                data-method="post"
                            >
                            <i class="fa fa-times"></i>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
        </li>
        <li>
            <div>
                <?php $cost = $service->getCost(); ?>
                <table class="table table-bordered">
                    <tr>
                        <td class="text-right">
                            <strong>Под итог:</strong>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($cost->getOrigin()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <?php foreach ($cost->getDiscounts() as $discount) : ?>
                    <tr>
                        <td class="text-right">
                            <strong><?= Html::encode($discount->getName()) ?>:</strong>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($discount->getValue()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <tr>
                        <td class="text-right">
                            <strong>Всего:</strong>
                        </td>
                        <td class="text-right">
                            <?= PriceHelper::format($cost->getTotal()) ?>
                            &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
                        </td>
                    </tr>
                </table>
                <p class="test-right">
                    <a href="<?= Url::to(['/shop/cart/index']) ?>">
                        <strong>
                            <i class="fa fa-shopping-cart"></i>
                            &nbsp;Моя корзина
                        </strong>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="<?= Url::to(['/shop/checkout/index']) ?>">
                        <strong>
                            <i class="fa fa-share"></i>
                            &nbsp;Купить
                        </strong>
                    </a>
                </p>
            </div>
        </li>
    </ul>
</div>
