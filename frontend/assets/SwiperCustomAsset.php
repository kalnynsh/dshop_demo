<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Swipe custom asset bundle.
 */
class SwiperCustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/swiper/opencart.css',
    ];

    public $js = [
        'js/swiper/swiperMain.js',
    ];

    public $depends = [
        'frontend\assets\SwiperAsset',
    ];
}
