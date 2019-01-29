<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model  \shop\forms\auth\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-6">
        <h2>Для нового покупателя</h2>
        <p><strong>Зарегистрироваться</strong></p>
        <p>
            Создание нового аккаунта позволит быстро создавать и отслеживать Ваши заказы.
        </p>
        <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>"
            class="btn btn-primary">
            Продолжить
        </a>
        <div class="well social-login">
            <h2>Регистрация с помощью соц сетей</h2>
            <?= AuthChoice::widget([
                'baseAuthUrl' => ['auth/network/auth']
            ]); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well">
            <h2>Для зарегистрированного покупателя</h2>
            <p><strong>Введите Ваши данные</strong></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999; margin: 1em 0">
                Если Вы забыли свой пароль, то Вы можете
                <?= Html::a('его сбросить.', [Url::to(['auth/reset/request'])]) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton(
                    'Войти',
                    ['class' => 'btn btn-primary', 'name' => 'login-button']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
