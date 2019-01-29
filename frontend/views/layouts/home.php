<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\Shop\FeaturedProductsWidget;
use frontend\widgets\Blog\LastPostsWidget;
use frontend\assets\SwiperCustomAsset;

SwiperCustomAsset::register($this);
?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
  <div id="content" class="col-sm-12">
    <div class="swiper-viewport">
        <div id="slideshow0" class="swiper-container swiper-container-horizontal">
            <div class="swiper-wrapper">
                <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-next swiper-slide-duplicate-prev"
                    data-swiper-slide-index="1">
                    <a href="#">
                        <img src="<?=Yii::getAlias('@static/cache/catalog/banners/iPhone6.jpg')?>"
                          alt="iPhone 6" class="img-responsive">
                    </a>
                </div>
                <div class="swiper-slide text-center swiper-slide-duplicate-active"
                  data-swiper-slide-index="0">
                  <a href="#">
                    <img src="<?=Yii::getAlias('@static/cache/catalog/banners/AcerSB220Q.jpg')?>"
                      alt="display AcerSB220Q" class="img-responsive">
                    </a>
                </div>
                <div class="swiper-slide text-center swiper-slide-prev swiper-slide-duplicate-next"
                  data-swiper-slide-index="1">
                  <a href="#">
                        <img src="<?=Yii::getAlias('@static/cache/catalog/banners/iPhone6.jpg')?>"
                          alt="iPhone 6" class="img-responsive">
                    </a>
                </div>
                <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-active"
                  data-swiper-slide-index="0">
                  <a href="#">
                    <img src="<?=Yii::getAlias('@static/cache/catalog/banners/AcerSB220Q.jpg')?>"
                      alt="display AcerSB220Q" class="img-responsive">
                    </a>
                </div>
            </div>
        </div>
      <div class="swiper-pagination slideshow0 swiper-pagination-clickable swiper-pagination-bullets">
        <span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
        <span class="swiper-pagination-bullet"></span>
      </div>
      <div class="swiper-pager">
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>

    <h3>Популярное</h3>
    <?= FeaturedProductsWidget::widget([
      'limit' => 4,
    ]) ?>

    <h3>Последние посты</h3>
    <?= LastPostsWidget::widget([
      'limit' => 4,
    ]) ?>

    <div class="swiper-viewport">
      <div id="carousel0" class="swiper-container swiper-container-horizontal">
        <div class="swiper-wrapper">
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-prev"
            data-swiper-slide-index="6">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/harley.png')?>"
              alt="Harley Davidson" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-active"
            data-swiper-slide-index="7">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/dell.png')?>"
              alt="Dell" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-next"
            data-swiper-slide-index="8">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/disney.png')?>"
              alt="Disney" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="9">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/starbucks.png')?>" alt="Starbucks" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="10">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/nintendo.png')?>"
              alt="Nintendo" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="0">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/nfl.png')?>"
              alt="NFL" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="1">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/redbull.png')?>"
              alt="RedBull" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="2">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/sony.png')?>"
              alt="Sony" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="3">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/cocacola.png')?>"
              alt="Coca Cola" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="4">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/burgerking.png')?>"
              alt="Burger King" class="img-responsive">
          </div>
          <div class="swiper-slide text-center" data-swiper-slide-index="5">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/canon.png')?>"
              alt="Canon" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-prev"
            data-swiper-slide-index="6">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/harley.png')?>"
              alt="Harley Davidson" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-active"
            data-swiper-slide-index="7">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/dell.png')?>"
              alt="Dell" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate swiper-slide-duplicate-next"
            data-swiper-slide-index="8">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/disney.png')?>"
              alt="Disney" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="9">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/starbucks.png')?>" alt="Starbucks" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="10">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/nintendo.png')?>"
              alt="Nintendo" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="0">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/nfl.png')?>"
              alt="NFL" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="1">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/redbull.png')?>"
              alt="RedBull" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="2">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/sony.png')?>"
              alt="Sony" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="3">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/cocacola.png')?>" alt="Coca Cola" class="img-responsive">
          </div>
          <div class="swiper-slide text-center swiper-slide-duplicate" data-swiper-slide-index="4">
            <img src="<?=Yii::getAlias('@static/cache/catalog/manufacturer/burgerking.png')?>"
              alt="Burger King" class="img-responsive">
          </div>
        </div>
      </div>
      <div class="swiper-pagination carousel0 swiper-pagination-clickable swiper-pagination-bullets">
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
        <span class="swiper-pagination-bullet"></span>
      </div>
      <div class="swiper-pager">
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>

    <?= $content ?>

  </div>
</div>

<?php $this->endContent() ?>
