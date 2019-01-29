<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\User\UserEditForm */
/* @var $user shop\entities\User\User */

$this->title = 'Обновление данных: ' .  $user->username;

$this->params['breadcrumbs'][] = [
    'label' => 'Пользователи',
    'url' => ['index']
];

$this->params['breadcrumbs'][] = [
    'label' => $user->username,
    'url' => ['view', 'id' => $user->id]
];

$this->params['breadcrumbs'][] = 'Обновление';
?>

<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

        <?=
            $form->field($model, 'username')->textInput(['maxLength' => true]);
        ?>

        <?=
            $form->field($model, 'email')->textInput(['maxLength' => true]);
        ?>

        <?=
            $form->field($model, 'role')->dropDownList($model->rolesList());
        ?>

        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']); ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
