<?php

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use shop\helpers\PriceHelper;

/* @var $products shop\entities\Shop\Product\Product[] */
?>

<div class="row">
    <?php foreach ($products as $product) : ?>
        <?php $url = Url::to(['shop/catalog/product', 'id' => $product->id]); ?>
        <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb transition">
                <?php if ($product->mainPhoto) : ?>
                    <div class="image">
                        <a href="<?= Html::encode($url) ?>">
                            <img src="<?= Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>"
                                alt="product image" class="img-responsive"/>
                        </a>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="caption">
                        <h4>
                            <a href="<?= Html::encode($url) ?>">
                                <?= Html::encode(StringHelper::truncateWords($product->name, 14)) ?>
                            </a>
                        </h4>
                        <p>
                            <?= Html::encode(StringHelper::truncateWords(strip_tags($product->description), 20)) ?>
                        </p>
                        <p class="price">
                            <span class="price-new">
                                <?= PriceHelper::format($product->price_new) ?>&nbsp;<i class="fa fa-rub"></i>
                            </span>
                            <?php if ($product->price_old) : ?>
                                <span class="price-old">
                                    <?= PriceHelper::format($product->price_old) ?>&nbsp;<i class="fa fa-rub"></i>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="button-group">
                        <button type="button" data-toggle="tooltip" title="Добавить в корзину"
                            href="<?= Url::to(['/shop/cart/add', 'id' => $product->id]) ?>"
                            data-method="post">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="hidden-xs hidden-sm hidden-md">В корзину</span>
                        </button>
                        <button type="button" data-toggle="tooltip" title="Добавить в список желаний"
                            href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>"
                            data-method="post">
                                <i class="fa fa-heart"></i>
                        </button>
                        <!-- <button type="button" data-toggle="tooltip" title="Сравнить"
                            onclick="compare.add('<?= $product->id ?>');">
                                <i class="fa fa-exchange"></i>
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
