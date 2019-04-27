<?php

/* @var $this yii\we\View */
/* $model shop\forms\manage\UserProfile\PrifileEditForm */
/* $user shop\entities\User\User */

use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = 'Профиль';
?>
<div class="profile-edit">
    <div class="col-sm-6">

        <?php $form = ActiveForm::begin() ?>

            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

            <?= $form->field(
                $model,
                'phone',
                [
                    'addon' => ['prepend' => ['content' => '+']]
                    ])->textInput(['maxLength' => true]
            ) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php $form = ActiveForm::end() ?>

    </div>
</div>
