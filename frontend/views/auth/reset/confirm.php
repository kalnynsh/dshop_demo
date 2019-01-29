<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста придумайте новый пароль:</p>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <?= $form->field($model, 'password')
            ->passwordInput(['autofocus' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
