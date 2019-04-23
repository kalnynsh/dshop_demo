<?php

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $model shop\forms\auth\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-sm-10">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>Для регистрации заполните ниже приведенные поля:</p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $from->field($model, 'phone', ['addon' => ['prepend' => ['content' => '+']]]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton(
                'Зарегистрироваться',
                ['class' => 'btn btn-primary', 'name' => 'signup-button']
            ) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
