<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\widgets\Shop\CartWidget;

AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="<?=Yii::$app->language?>">
<!--<![endif]-->
  <head>
    <meta charset="<?= \Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="canonical" href="<?= Html::encode(Url::canonical()) ?>">
    <link rel="icon" href="<?= \Yii::getAlias('@web/image/logo.ico') ?>">
    <?php $this->head() ?>
  </head>
  <body>
    <?php $this->beginBody()?>
    <div class="allButFooter">
      <nav id="top">
        <div class="container">
          <!--<div class="pull-left">
            <form action="#"
              method="post" enctype="multipart/form-data" id="form-currency">
              <div class="btn-group">
                <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                  <strong>$</strong>
                  <span class="hidden-xs hidden-sm hidden-md">Currency</span>
                  &nbsp;<i class="fa fa-caret-down"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <button class="currency-select btn btn-link btn-block" type="button" name="EUR">€ Euro</button>
                  </li>
                  <li>
                    <button class="currency-select btn btn-link btn-block" type="button" name="GBP">
                      £ Pound Sterling
                    </button>
                  </li>
                  <li>
                    <button class="currency-select btn btn-link btn-block" type="button" name="USD">$ US Dollar</button>
                  </li>
                </ul>
              </div>
              <input type="hidden" name="code" value="">
              <input type="hidden" name="redirect" value="/site/index">
            </form>
          </div> -->

          <div id="top-links" class="nav pull-right">
            <ul class="list-inline">
              <li>
                <a href="<?= Html::encode(Url::to(['/contact/index'])) ?>">
                  <i class="fa fa-phone"></i>
                </a>
                <span class="hidden-xs hidden-sm hidden-md">+7-983-456-72-11</span>
              </li>
              <li class="dropdown">
                <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>"
                  title="Войти" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user"></i>
                    <span class="hidden-xs hidden-sm hidden-md">Мой аккаунт</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                  <?php if (Yii::$app->user->isGuest) : ?>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>">
                        Войти
                      </a>
                    </li>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>">
                        Зарегистрироваться
                      </a>
                    </li>
                  <?php else : ?>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>">
                        Личный кабинет
                      </a>
                    </li>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" data-method="post">
                        Выйти
                      </a>
                    </li>
                  <?php endif;?>
                </ul>
              </li>
              <li>
                <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>"
                id="wishlist-total" title="Список желаний">
                  <i class="fa fa-heart"></i>
                  <span class="hidden-xs hidden-sm hidden-md">Список желаний</span>
                </a>
              </li>
              <li>
                <a href="<?= Html::encode(Url::to(['/shop/cart/index'])) ?>" title="Моя корзина">
                  <i class="fa fa-shopping-cart"></i>
                  <span class="hidden-xs hidden-sm hidden-md">Моя корзина</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <header>
        <div class="container">
          <div class="row">
            <div class="col-sm-4">
              <div class="logo">
                  <a href="<?= Url::home() ?>">
                    <img src="<?= \Yii::getAlias('@web/image/logo.png') ?>">
                  </a>
                  <span class="logo-text">
                    Демонстрационный сайт
                    <a href="<?= Html::encode(Url::home()) ?>">
                      <span class="logo-text-label">D`shop</span>
                    </a>
                  </span>
              </div>
            </div>
            <div class="col-sm-5">
              <?= Html::beginForm(['/shop/catalog/search'], 'get') ?>
              <div id="search" class="input-group">
                <input type="text" name="text" value="" placeholder="Поиск"
                  class="form-control input-lg" />
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default btn-lg">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
              <?= Html::endForm() ?>
            </div>
            <div class="col-sm-3">
              <?= CartWidget::widget() ?>
            </div>
          </div>
        </div>
      </header>

      <div class="container">
        <?php
          NavBar::begin([
            'options' => [
              'screenReaderToggleText' => 'Menu',
              'id' => 'menu',
              'class' => 'navbar',
            ],
          ]);
          echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav'],
            'items' => [
              ['label' => 'Главная', 'url' => ['/site/index']],
              ['label' => 'Каталог', 'url' => ['/shop/catalog/index']],
              ['label' => 'Блог', 'url' => ['/blog/post/index']],
              ['label' => 'Контакт', 'url' => ['/contact/index']],
              ['label' => 'О нас', 'url' => ['/page/view', 'id' => 3]],
              ['label' => 'Новости', 'url' => ['/page/view', 'id' => 4]],
            ],
          ]);
          NavBar::end();
        ?>
      </div>

      <div class="container">
        <div>
          <?= Breadcrumbs::widget([
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          ]) ?>
        </div>
        <div>
          <?= Alert::widget() ?>
        </div>
      </div>

      <div class="container">
        <?= $content ?>
      </div>

    </div>

    <footer class="main-footer">
      <div class="container">
        <div class="row">
          <div class="col-sm-3">
            <h5>Информация</h5>
            <ul class="list-unstyled">
              <li>
                <a href="<?= Html::encode(Url::to(['/page/view', 'id' => 3])) ?>">
                  О нас
                </a>
              </li>
              <li>
                <a href="#">
                  Доставка
                </a>
              </li>
              <li>
                <a href="#">
                  Гарантия
                </a>
              </li>
              <li>
                <a href="#">
                  Услуги
                </a>
              </li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>Сервисы</h5>
            <ul class="list-unstyled">
              <li><a href="<?= Html::encode(Url::to(['/contact/index'])) ?>">Напишите нам</a></li>
              <li><a href="#">Карта сайта</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>Дополнительно</h5>
            <ul class="list-unstyled">
              <li><a href="<?= Html::encode(Url::to(['/page/view', 'id' => 4])) ?>">Новости</a></li>
              <li><a href="#">Акции</a></li>
              <li><a href="<?= Html::encode(Url::to(['/blog/post/index'])) ?>">Обзоры</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>Мой аккаунт</h5>
            <ul class="list-unstyled">

              <?php if (\Yii::$app->user->isGuest) : ?>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>">
                        Войти
                      </a>
                    </li>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>">
                        Зарегистрироваться
                      </a>
                    </li>
                <?php else : ?>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>">
                        Личный кабинет
                      </a>
                    </li>
                    <li>
                      <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" data-method="post">
                        Выйти
                      </a>
                    </li>
              <?php endif; ?>

              <li>
                <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>"
                  id="wishlist-total" title="Список желаний">
                    Список желаний
                </a>
              </li>

            </ul>
          </div>
        </div>
        <hr>
        <p>Разработано <a href="#">Калныньш Д</a> © <?= \date('Y') ?></p>
      </div>
    </footer>

    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
