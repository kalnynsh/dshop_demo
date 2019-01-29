<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $order shop\entities\Shop\Order\Order */
/* @var $model shop\forms\manage\Shop\Order\OrderEditForm */

$this->title = 'Обновление заказа (ID): ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $order->id, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>

<div class="order-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Информация о покупателе</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->customer, 'name')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model->customer, 'phone')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Информация о доставке</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model->delivery, 'address')->textarea(['rows' => 3]) ?>

                </div>
                <div class="col-md-4">
                    <?= $form->field($model->delivery, 'index')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->delivery, 'method')->dropDownList(
                        $model->delivery->list(),
                        ['prompt' => '--- Выбор ---']
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">Заметки</div>
                <div class="box-body">
                    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
