<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Swiper main asset bundle.
 */
class SwiperAsset extends AssetBundle
{
    public $sourcePath = '@bower/swiper/dist/';

    public $css = [
        'css/swiper.min.css',
    ];

    public $js = [
        'js/swiper.jquery.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
