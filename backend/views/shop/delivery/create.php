<?php

/** @var $this yii\web\View */
/** @var $model shop\forms\manage\Shop\DeliveryForm */

$this->title = 'Добавить доставку';
$this->params['breadcrumbs'][] = [
    'label' => 'Доставка',
    'url' => ['index'],
];

?>

<div class="delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
