<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user shop\entities\User\User */
/* @var $product shop\entities\Shop\Product\Product */

$link = \Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['shop/catalog/product', 'id' => $product->id]);
?>
<div class="wishlist-availabe">
    <p>Добрый день <?= Html::encode($user->name) ?>.</p>

    <p>Товар из Вашего списка желаний уже снова доступен.</p>

    <p>Ссылка на товар <?= Htl::a(Html::encode($link), $link) ?></p>
</div>
