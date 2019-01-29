<?php

/* @var $this yii\web\View */
/* @var $characteristic shop\entities\Shop\Characteristic */
/* @var $model shop\forms\manage\Shop\CharacteristicForm */

$this->title = 'Обновление характеристики: ' . $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Характеристики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->name, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>

<div class="characteristic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
