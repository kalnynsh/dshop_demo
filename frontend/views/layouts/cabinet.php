<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
    <aside id="column-right" class="col-sm-3 hidden-xs">
        <div class="list-group">
            <?php if (Yii::$app->user->isGuest) : ?>
            <a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>"
                class="list-group-item">
                Войти
            </a>
            <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>"
                class="list-group-item">
                Зарегистрироваться
            </a>
            <?php endif; ?>
            <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>"
                data-method="post" class="list-group-item">
                Выйти
            </a>
            <a href="<?= Html::encode(Url::to(['/auth/reset/request'])) ?>"
                class="list-group-item">
                Сбросить мой пароль
            </a>
            <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>"
                class="list-group-item">
                Мой аккаунт
            </a>
            <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>"
                class="list-group-item">
                Список желаний
            </a>
            <a href="<?= Html::encode(Url::to(['/cabinet/order'])) ?>"
                class="list-group-item">
                История заказов
            </a>
            <a href="#" class="list-group-item">Новости</a>
        </div>
    </aside>
</div>

<?php $this->endContent() ?>
