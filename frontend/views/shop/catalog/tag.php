<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $brand shop\entities\Shop\Tag */

use yii\helpers\Html;

$this->title = 'Товары с тегом ' . $tag->name;

$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url' => ['index'],
];

$this->params['breadcrumbs'][] = $tag->name;
?>

<h1>Товары с тегом &laquo;<?= Html::encode($tag->name) ?>&raquo;</h1>

<hr />

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>
