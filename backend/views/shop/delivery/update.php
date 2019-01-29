<?php

/** @var $this yii\web\View */
/** @var $method shop\entities\Shop\Delivery */
/** @var $model shop\forms\manage\Shop\DeliveryForm */

$this->title = 'Обновить данные доставки: ' . $method->name;

$this->params['breadcrumbs'][] = [
    'label' => 'Доставки',
    'url' => ['index'],
];

$this->params['breadcrumbs'][] = [
    'label' => $method->name,
    'url' => ['view', 'id' => $method->id]
];

$this->params['breadcrumbs'][] = 'Обновить';
?>

<div class="delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
