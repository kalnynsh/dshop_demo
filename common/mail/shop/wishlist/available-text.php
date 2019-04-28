<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user shop\entities\User\User */
/* @var $product shop\entities\Shop\Product\Product */

$link = \Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['shop/catalog/product', 'id' => $product->id]);
?>

Добрый день <?= Html::encode($user->name) ?>.

Товар из Вашего списка желаний уже снова доступен.

Ссылка на товар <?=  $link ?>.
